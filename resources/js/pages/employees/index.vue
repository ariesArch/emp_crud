<template>
	<div>
		<v-stepper v-model="step" elevation="4">
			<v-stepper-items>
				<v-stepper-content class="pa-0" step="1">
					<div v-shortkey="['enter']" @shortkey="termChange" />
					<v-data-table
						:headers="headers"
						:items="employees"
						:search="filters.search"
						:loading="loadingData"
						loading-text="Loading... Please wait"
						fixed-header
						:options.sync="employeeOptions"
						:server-items-length="employeeOptions.totalItems"
						@page-count="employeeOptions.pageCount = $event"
						hide-default-footer
					>
						<template #top>
							<v-toolbar flat>
								<v-autocomplete
									v-model="filters.company_id"
									label="Filter By Company"
									:items="companies"
									item-text="name"
									item-value="id"
									outlined
									dense
									hide-details
									class="mr-1"
									clearable
								/>
								<v-autocomplete
									v-model="filters.department_id"
									label="Filter By Department"
									:items="departments"
									item-text="name"
									item-value="id"
									outlined
									dense
									hide-details
									class="mr-1"
									clearable
								/>
								<v-text-field
									outlined
									append-icon
									v-model="filters.search"
									label="Search"
									single-line
									hide-details
									dense
								>
									<!-- <template #append>
										<v-icon color="info" @click="termChange">
											mdi-magnify
										</v-icon>
									</template>-->
								</v-text-field>
								<v-btn fab small @click="termChange" class="ml-1">
									<v-icon color="info">mdi-magnify</v-icon>
								</v-btn>
								<v-spacer />
								<v-btn color="primary" dark class="mb-2" @click="onNew" v-if="is_admin">Create</v-btn>
							</v-toolbar>
						</template>
						<template v-slot:item.index="{ item }">{{ employees.indexOf(item)+1 }}</template>
						<template v-slot:item.actions="{item}">
							<div class="d-flex">
								<v-icon color="primary" medium class="mr-2" @click="onDetail(item)">mdi-eye</v-icon>
								<v-icon
									color="error"
									medium
									class="mr-4"
									@click="onDelete(item)"
									v-if="user.id!==item.id"
								>mdi-delete</v-icon>
							</div>
						</template>
					</v-data-table>
					<v-divider />
					<div class="text-center">
						<v-pagination v-model="employeeOptions.page" :length="employeeOptions.pageCount"></v-pagination>
					</div>
				</v-stepper-content>
				<EmployeeForm
					:companies="companies"
					:departments="departments"
					:roles="roles"
					@onGoBack="handleGoBack"
					v-model="selectedItem"
				/>
			</v-stepper-items>
		</v-stepper>
		<SnackBar />
		<ConfirmDelete screen="Employee" />
	</div>
</template><script>
	import { mapGetters } from "vuex";
	import { employeeHeader } from "@/assets/util/tableHeaders";
	import EmployeeForm from "@/components/Forms/EmployeeForm";
	export default {
		name: "Employee",
		data: () => ({
			step: 1,
			// headers: employeeHeader,
			loadingData: false,
			employees: [],
			companies: [],
			departments: [],
			roles: [],
			employeeOptions: {
				itemsPerPage: 10,
				totalItems: 0,
				pageCount: 0,
			},
			filters: {
				search: "",
				company_id: "",
				department_id: "",
			},
			selectedItem: {},
		}),
		components: {
			EmployeeForm,
		},
		computed: {
			...mapGetters({ jwt: "auth/jwt" }),
			headers() {
				return employeeHeader.filter(
					(header) =>
						header.visible === true || header.visible === !this.is_admin
				);
			},
		},
		mounted() {
			this.$on("deletedData", (data) => {
				const foundIndex = this.employees.findIndex(
					(employee) => employee.id === data.id
				);
				this.employees.splice(foundIndex, 1);
			});
		},
		watch: {
			"employeeOptions.page": {
				handler(newVal, oldVal) {
					if (newVal === oldVal) {
						return;
					}
					this.paginationDataTable(
						this,
						`${this.$api.EMPLOYEE_URL}`,
						this.employeeOptions
					);
					// setTimeout(() => {
					// 	this.paginationDataTable(
					// 		this,
					// 		`${this.$api.EMPLOYEE_URL}`,
					// 		this.employeeOptions
					// 	);
					// }, 500);
				},
				deep: true,
			},
		},
		async created() {
			try {
				const { status, message, data } = await this.$request.getData(
					this.$api.MASTER_RECORD_URL,
					this.jwt
				);
				if (status === 1) {
					this.companies = data.companies;
					this.departments = data.departments;
					this.roles = data.roles;
				} else {
					this.handleStatus({
						status,
						message,
						that: this,
						successMessage: "",
					});
				}
			} catch (error) {
				this.handleException({ error, that: this });
			}
			// try {
			// 	const response = await this.$request.getData(
			// 		this.$api.COMPANY_URL,
			// 		this.jwt
			// 	);
			// 	if (response.status === 1) {
			// 		this.companies = response.data;
			// 	} else {
			// 		this.handleStatus({
			// 			status: response.status,
			// 			message,
			// 			that: this,
			// 			successMessage: "",
			// 		});
			// 	}
			// } catch (error) {
			// 	this.handleException({ error, that: this });
			// }
		},
		methods: {
			termChange() {
				this.paginationDataTable(
					this,
					`${this.$api.EMPLOYEE_URL}`,
					this.employeeOptions
				);
			},
			async getDataFromApi(url) {
				try {
					this.loadingData = true;
					const { data, status, message, meta } =
						await this.$request.getData(url, this.jwt);
					if (status === 1) {
						setTimeout(() => {
							this.employees = data;
							this.setPaginationData(
								this,
								meta,
								this.employeeOptions
							);
						}, 100);
					} else {
						this.loadingData = false;
						this.handleStatus({ status, message, that: this });
					}
				} catch (error) {
					this.handleException({ error, that: this });
				}
				this.loadingData = false;
			},
			async fetchData() {
				try {
					const { status, message, data } = await this.$request.getData(
						this.$api.EMPLOYEE_URL,
						this.jwt
					);
					if (status === 1) {
						this.employees = data;
					} else {
						this.handleStatus({
							status,
							message,
							that: this,
							successMessage: "",
						});
					}
				} catch (error) {
					this.handleException({ error, that: this });
				}
			},
			onNew() {
				this.step = 2;
			},
			onDetail(item) {
				const employee = (({
					id,
					name,
					first_name,
					last_name,
					email,
					phone,
					department,
					role,
					company,
					address,
				}) => ({
					id,
					name,
					first_name,
					last_name,
					email,
					phone,
					department,
					role,
					company,
					address,
				}))(item);
				this.step = 2;
				this.selectedItem = JSON.parse(JSON.stringify(employee));
			},
			onDelete(item) {
				this.showConfirmDelete = true;
				this.$emit("deleteForm", item, this.$api.EMPLOYEE_URL, this.jwt);
			},
			handleGoBack(data = null, action = null) {
				this.step = 1;
				if (data === null && action === null) {
					return;
				}
				if (action === "updated") {
					const foundIndex = this.employees.findIndex(
						(employee) => employee.id === data.id
					);
					this.employees.splice(foundIndex, 1, {
						...data,
					});
				} else {
					// this.employees.unshift({ ...data });
					this.paginationDataTable(
						this,
						`${this.$api.EMPLOYEE_URL}`,
						this.employeeOptions
					);
				}
			},
		},
	};
</script>

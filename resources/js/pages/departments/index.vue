<template>
	<div>
		<v-stepper v-model="step" elevation="4">
			<v-stepper-items>
				<v-stepper-content class="pa-0" step="1">
					<div v-shortkey="['enter']" @shortkey="termChange" />
					<v-data-table
						:headers="headers"
						:items="departments"
						:search="filters.search"
						:loading="loadingData"
						loading-text="Loading... Please wait"
						fixed-header
						:options.sync="departmentOptions"
						:server-items-length="departmentOptions.totalItems"
						@page-count="departmentOptions.pageCount = $event"
						hide-default-footer
					>
						<template #top>
							<v-toolbar flat>
								<v-text-field v-model="filters.search" label="Search" single-line hide-details>
									<template #append>
										<v-icon color="info" @click="termChange">
											<!-- {{ filters.search?'mdi-close':'mdi-magnify' }} -->
											mdi-magnify
										</v-icon>
									</template>
								</v-text-field>
								<v-spacer />
								<v-btn color="primary" dark class="mb-2" @click="onNew" v-if="is_admin">Create</v-btn>
							</v-toolbar>
						</template>
						<template v-slot:item.index="{ item }">{{ departments.indexOf(item)+1 }}</template>
						<template v-slot:item.actions="{item}">
							<div class="d-flex d-flex-row justify-end">
								<v-icon color="primary" medium class="mr-2" @click="onDetail(item)">mdi-eye</v-icon>
								<v-icon color="error" medium class="mr-4" @click="onDelete(item)">mdi-delete</v-icon>
							</div>
						</template>
					</v-data-table>
					<v-divider />
					<div class="text-center">
						<v-pagination v-model="departmentOptions.page" :length="departmentOptions.pageCount"></v-pagination>
					</div>
				</v-stepper-content>
				<DepartmentForm @onGoBack="handleGoBack" v-model="selectedItem" />
			</v-stepper-items>
		</v-stepper>
		<SnackBar />
		<ConfirmDelete screen="Department" />
	</div>
</template><script>
	import { mapGetters } from "vuex";
	import { departmentHeader } from "@/assets/util/tableHeaders";
	import DepartmentForm from "@/components/Forms/DepartmentForm";
	export default {
		name: "Department",
		data: () => ({
			step: 1,
			loadingData: false,
			departments: [],
			departmentOptions: {
				itemsPerPage: 10,
				totalItems: 0,
				pageCount: 0,
			},
			filters: {
				search: "",
			},
			selectedItem: {},
		}),
		components: {
			DepartmentForm,
		},
		computed: {
			...mapGetters({ jwt: "auth/jwt" }),
			headers() {
				return departmentHeader.filter(
					(header) =>
						header.visible === true || header.visible === !this.is_admin
				);
			},
		},
		mounted() {
			this.$on("deletedData", (data) => {
				const foundIndex = this.departments.findIndex(
					(department) => department.id === data.id
				);
				this.departments.splice(foundIndex, 1);
			});
		},
		watch: {
			"departmentOptions.page": {
				handler(newVal, oldVal) {
					if (newVal === oldVal) {
						return;
					}
					this.paginationDataTable(
						this,
						`${this.$api.DEPARTMENT_URL}`,
						this.departmentOptions
					);
					// setTimeout(() => {
					// 	this.paginationDataTable(
					// 		this,
					// 		`${this.$api.DEPARTMENT_URL}`,
					// 		this.departmentOptions
					// 	);
					// }, 500);
				},
				deep: true,
			},
		},
		methods: {
			termChange() {
				this.paginationDataTable(
					this,
					`${this.$api.DEPARTMENT_URL}`,
					this.departmentOptions
				);
			},
			async getDataFromApi(url) {
				try {
					this.loadingData = true;
					const { data, status, message, meta } =
						await this.$request.getData(url, this.jwt);
					if (status === 1) {
						setTimeout(() => {
							this.departments = data;
							this.setPaginationData(
								this,
								meta,
								this.departmentOptions
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
						this.$api.DEPARTMENT_URL,
						this.jwt
					);
					if (status === 1) {
						this.departments = data;
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
				const department = (({ id, name, email, address }) => ({
					id,
					name,
					email,
					address,
				}))(item);
				this.step = 2;
				this.selectedItem = JSON.parse(JSON.stringify(department));
			},
			onDelete(item) {
				this.showConfirmDelete = true;
				this.$emit("deleteForm", item, this.$api.DEPARTMENT_URL, this.jwt);
			},
			handleGoBack(data = null, action = null) {
				this.step = 1;
				if (data === null && action === null) {
					return;
				}
				if (action === "updated") {
					const foundIndex = this.departments.findIndex(
						(department) => department.id === data.id
					);
					this.departments.splice(foundIndex, 1, {
						...data,
					});
				} else {
					// this.departments.unshift({ ...data });
					this.paginationDataTable(
						this,
						`${this.$api.DEPARTMENT_URL}`,
						this.departmentOptions
					);
				}
			},
		},
	};
</script>

<template>
	<v-row class="justify-content-center">
		<v-col cols="12">
			<div v-shortkey="['enter']" @shortkey="termChangeEnter" />
			<v-data-table
				:headers="headers"
				:items="companies"
				:search="filters.search"
				:loading="loadingData"
				loading-text="Loading... Please wait"
				fixed-header
				class="elevation-1"
				:options.sync="companyOptions"
				:server-items-length="companyOptions.totalItems"
			>
				<template #top>
					<v-toolbar flat>
						<v-text-field v-model="filters.search" label="Search" single-line hide-details>
							<template #append>
								<v-icon color="info" @click="termChangeEnter">
									<!-- {{ filters.search?'mdi-close':'mdi-magnify' }} -->
									mdi-magnify
								</v-icon>
							</template>
						</v-text-field>
						<v-spacer />
						<v-btn color="primary" dark class="mb-2" @click="onNew">Create</v-btn>
					</v-toolbar>
				</template>
				<template v-slot:item.actions="{item}">
					<v-icon color="primary" medium class="mr-2" @click="onEdit(item)">mdi-pencil</v-icon>
					<v-icon color="error" medium class="mr-4" @click="onDelete(item)">mdi-delete</v-icon>
				</template>
			</v-data-table>
		</v-col>
		<SnackBar />
		<ConfirmDelete screen="Department" />
		<CompanyForm @onCreate="createData" @onUpdate="updateData" />
	</v-row>
</template>
<script>
	import { mapGetters } from "vuex";
	import { companyHeader } from "@/assets/util/tableHeaders";
	import CompanyForm from "@/components/Forms/CompanyForm";
	export default {
		name: "Department",
		data: () => ({
			headers: companyHeader,
			loadingData: false,
			companies: [],
			companyOptions: {
				itemsPerPage: 10,
				totalItems: 0,
			},
			filters: {
				search: "",
			},
		}),
		components: {
			CompanyForm,
		},
		computed: {
			...mapGetters({ jwt: "auth/jwt" }),
		},
		watch: {
			"companyOptions.page": {
				handler(newVal, oldVal) {
					console.log(newVal);

					if (newVal === oldVal) {
						return;
					}
					setTimeout(() => {
						this.paginationDataTable(
							this,
							`${this.$api.COMPANY_URL}`,
							this.companyOptions
						);
					}, 500);
				},
				deep: true,
			},
		},
		methods: {
			termChange() {
				this.paginationDataTable(
					this,
					`${this.$api.COMPANY_URL}`,
					this.companyOptions
				);
			},
			termChangeEnter() {
				this.termChange();
			},
			async getDataFromApi(url) {
				try {
					this.loadingData = true;
					const { data, status, message, meta } =
						await this.$request.getData(url, this.jwt);
					if (status === 1) {
						setTimeout(() => {
							this.companies = data;
							this.setPaginationData(this, meta, this.companyOptions);
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
						this.$api.COMPANY_URL,
						this.jwt
					);
					if (status === 1) {
						this.companies = data;
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
				this.$emit("newForm");
			},
			createData(data) {
				this.companies.push(data);
			},
			updateData(data) {
				const foundIndex = this.companies.findIndex(
					(department) => department.id === data.id
				);
				this.companies.splice(foundIndex, 1, data);
			},
			onEdit(item) {
				const department = (({ id, name }) => ({
					id,
					name,
				}))(item);
				this.$emit("editForm", department);
			},
			onDelete(item) {
				this.showConfirmDelete = true;
				this.$emit("deleteForm", item, this.$api.COMPANY_URL, this.jwt);
			},
		},
	};
</script>
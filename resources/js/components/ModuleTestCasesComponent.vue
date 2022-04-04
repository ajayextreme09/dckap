<template>
		<div>
				<div class="row">
						<div class="col-md-4">
								<div class="panel panel-success">
								<div class="panel-heading">
										<h3 class="panel-title">Modules</h3>
								</div>
								<div class="panel-body">
									<v-jstree :data="data2" allow-batch whole-row @item-click="itemClick"></v-jstree>
								</div>
						</div>
						</div>
				<div class="col-md-8">
					<div class="panel panel-success">
								<div class="panel-heading">
										<h3 class="panel-title">{{module_tree_selected}} <span v-if="test_cases.length>0"> ({{test_cases.length}})</span></h3>
										<span class="pull-right clickable" @click="addTestCase"><i class="fa fa-plus-square"></i></span>
								</div>
								<div class="panel-body np">
									<div v-if="test_cases.length==0" class="test-cases">
										No Test Cases Found
									</div>
									<div v-else class="test-cases">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Test Case</th>
													<th style="width: 5%;">Edit</th>
													<th style="width: 5%;">Delete</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="(dt, index) in test_cases">
													<td>{{dt.name}}</td>
													<td class="text-center"><a href="#" @click="editTestCase(dt)"><i class="fa fa-pencil text-info"></i></a></td>
													<td class="text-center"><a href="#" @click="deleteTestCase(dt, index)"><i class="fa fa-trash text-danger"></i></a></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
						</div>
				</div>
				</div>

				<div v-if="showModal">
					<transition name="modal">
						<div class="modal-mask">
							<div class="modal-wrapper">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title"> Add Test Case</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true" @click="showModal = false">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="form-group" :class="(errors.parent_module_err)?'has-error':''">
												<label >Select Module</label>
												<treeselect v-model="parent_module" :options="data2" :normalizer="normalizer" />
												<span class="text-danger" v-if="errors.parent_module_err">Please select parent module</span>
											</div>
											<div class="form-group" :class="(errors.test_case_err)?'has-error':''">
												<label>Enter Test Case</label>
												<input type="text" class="form-control" v-model="test_case" />
												<span class="text-danger" v-if="errors.test_case_err">Please enter test case</span>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" @click="showModal = false">Close</button>
											<button type="button" class="btn btn-primary" @click="addUpdateModule">{{(test_case_id==0)?'Add Test Case':'Update Test Case'}}</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</transition>
				</div>
		</div>
</template>

<script>
import VJstree from 'vue-jstree';
import Treeselect from '@riophae/vue-treeselect';
import '@riophae/vue-treeselect/dist/vue-treeselect.css';
import Toasted from 'vue-toasted';
import Vue from 'vue';

Vue.use(Toasted);

export default {
		mounted() {
		console.log("Component mounted.");
	},

	components: {
	 VJstree,
	 Treeselect
	},
	data() {
		return {
				baseUrl: document.head.querySelector('meta[name="base-url"]').content,
				data2: [],
				showModal: false,
				parent_module: null,
				test_case: null,
				is_root_module: false,
				test_cases: [],
				module_tree_selected: "Test Cases",
				test_case_id: 0,
				module_node: null,
				errors: {
					parent_module_err: false,
					test_case_err: false
				},
				normalizer(node) {
			return {
				id: node.id,
				label: node.text,
				children: node.children,
			}
		},
		};
	},
	created() {
		document.getElementById("mnuTestCases").classList.add("active");
		this.getModuleList();
	},
	methods: {
				getModuleList: async function () {
						let promise = axios.get(this.baseUrl + "/modules/list");
						promise.then((response) => {
										if (response) {
												console.log(response);
										if (response.data.success) {
												this.data2 = response.data.modules;
										} else {
											this.data2 = [];
										}
										}
								});
								promise.catch((error) => {
									this.$toasted.error('Somethins went wrong. Please try again.').goAway(3000);
						});
				},

		addUpdateModule: async function () {
				this.errors.parent_module_err = false;
				this.errors.test_case_err = false;
				if(!this.parent_module || this.parent_module=="") {
					this.errors.parent_module_err = true;
					this.$toasted.error('Please select parent module').goAway(3000);
				}
				if(!this.test_case || this.test_case=="") {
					this.errors.test_case_err = true;
					this.$toasted.error('Please enter test case').goAway(3000);
				}
				if(!this.errors.parent_module_err && !this.errors.test_case_err) {
						let body = {
							'id': this.test_case_id,
							'name': this.test_case,
							'module_id': this.parent_module
						}
						let promise = axios.post(this.baseUrl + "/testcase/save", body);
						promise.then((response) => {
										if (response) {
										if (response.data.success) {
											this.showModal = false;
											this.test_case = null;
											this.getTestCases(this.module_node);

											this.$toasted.success(response.data.message).goAway(3000);
												// this.module_list.push(response.data.module);
										} else {
											this.$error.error(response.data.message).goAway(3000);
										}
										}
								});
								promise.catch((error) => {
									this.$toasted.error('Somethins went wrong. Please try again.').goAway(3000);
						});
				}
		},

		deleteTestCase: async function (module_val, index) {
			if(confirm("Do you really want to delete?")){
				let promise = axios.delete(this.baseUrl + "/testcase/delete/" + module_val.id);
				promise.then((response) => {
								if (response) {
								if (response.data.success) {
										this.test_cases.splice(index, 1);
										this.$toasted.success(response.data.message).goAway(3000);
								} else {
									this.$error.error(response.data.message).goAway(3000);
								}
								}
						});
						promise.catch((error) => {
							this.$toasted.error('Somethins went wrong. Please try again.').goAway(3000);
				});
			}
		},

		itemClick: async function (node) {
			// console.log(node.model);
			this.parent_module = node.model.id;
			this.module_node = node;
			this.getTestCases(node);	
		},

		getTestCases: async function (node) {
			let promise = axios.get(this.baseUrl + "/test_cases/" + node.model.id);
			promise.then((response) => {
							if (response) {
								console.log(response);
							if (response.data.success) {
									this.test_cases = response.data.testCases;
									// this.module_tree_selected = node.model.text + " (" + node.model.test_cases_count + ")";
									this.module_tree_selected = node.model.text;
									// this.parent_module = node.model.id;
							} else {
								this.module_tree_selected = "Test Cases";
							}
							}
					});
					promise.catch((error) => {
						this.$toasted.error('Somethins went wrong. Please try again.').goAway(3000);
			});
		},

		addTestCase: async function () {
			this.showModal = true;
			this.test_case_id = 0;
		},

		editTestCase: async function (test_case) {
			this.errors.parent_module_err = false;
			this.errors.module_name_err = false;
			let promise = axios.get(this.baseUrl + "/testcase/details/" + test_case.id);
			promise.then((response) => {
							if (response) {
								console.log(response.data.testCase);
							if (response.data.success) {
									this.test_case = response.data.testCase.name;
									this.showModal = true;
									this.test_case_id = test_case.id;
									// this.parent_module = node.model.id;
							} else {
								this.module_tree_selected = "Test Cases";
							}
							}
					});
					promise.catch((error) => {
						this.$toasted.error('Somethins went wrong. Please try again.').goAway(3000);
			});
		},
	},

};
</script>

<style scoped>
.modal-mask {
	position: fixed;
	z-index: 9998;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0, 0, 0, .5);
	display: table;
	transition: opacity .3s ease;
}

.modal-wrapper {
	display: table-cell;
	vertical-align: middle;
}

.panel-heading span {
		margin-top: -26px;
		font-size: 15px;
		margin-right: -12px;
}

.clickable {
		background: rgba(0, 0, 0, 0.15);
		display: inline-block;
		padding: 6px 12px;
		border-radius: 4px;
		cursor: pointer;
}

.np {
	padding: 0;
}

.test-cases {
	padding: 10px;
}
</style>
<template>
		<div>
				<div class="row">
						<div class="col-md-4">
							<div class="panel panel-success">
								<div class="panel-heading">
										<h3 class="panel-title">Parent Modules</h3>
										<span class="pull-right clickable" @click="showModal = true"><i class="fa fa-plus-square"></i></span>
										<span class="pull-right clickable btn-delete" @click="deleteModules"><i class="fa fa-trash text-danger"></i></span>
								</div>
								<div class="panel-body panel-body-grey">
									<div id="0">
									<draggable  :options="{handle:'.dragg-me'}" v-model="data2" group="category" @start="drag=true" @end="end" @change="afterAdd" :move="checkMove">
										<div v-for="dt in data2" :id="'module-id-'+dt.id" class="card">
											<div class="row">
												<div class="col-md-1">
													<span class="glyphicon glyphicon-move dragg-me" aria-hidden="true"></span>
												</div>
												<div class="col-md-1">
													<input type="checkbox" :id="dt.id" :value="dt.id" v-model="chk_module">
												</div>
												<div class="col-md-9" @click="detailsModule(dt, 0)">
													{{dt.text}} ({{dt.childrens_count}})
												</div>
											</div>
										</div>
									</draggable>
									</div>
								</div>
						</div>
						</div>
				<div
					v-for="div in divs"
					:key="div.id"
					class="col-md-4"
				>
							<div class="panel panel-success">
								<div class="panel-heading">
										<h3 class="panel-title">{{div.name}}</h3>
								</div>
								<div class="panel-body panel-body-grey">
									<div :id="div.id">
										<draggable  :options="{handle:'.dragg-me'}" v-model="div.data" group="category" @start="drag=true" @end="end" @change="afterAdd" :move="checkMove">
											<div v-for="(dt, index) in div.data" :id="'module-id-'+dt.id"  class="card">
												<div class="row">
													<div class="col-md-1">
														<span class="glyphicon glyphicon-move dragg-me" aria-hidden="true"></span>
													</div>
													<div class="col-md-10">
														<span @click="detailsModule(dt, div.id)">{{dt.text}} ({{dt.childrens_count}})</span>
													</div>
												</div>
											</div>
										</draggable>
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
											<h5 class="modal-title"> Add Module/Submodule</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true" @click="showModal = false">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="form-group">
												<label><input type="checkbox" id="checkbox" v-model="is_root_module"> Use as root element</label>
											</div>
											<div class="form-group" v-show="!is_root_module" :class="(errors.parent_module_err)?'has-error':''">
												<label>Select Parent Module</label>
												<treeselect v-model="parent_module" :options="data2" :normalizer="normalizer" />
												<span class="text-danger" v-if="errors.parent_module_err">Please select parent module</span>
											</div>
											<div class="form-group" :class="(errors.module_name_err)?'has-error':''">
												<label>Enter Module Name</label>
												<input type="text" class="form-control" v-model="module_name" />
												<span class="text-danger" v-if="errors.module_name_err">Please enter module name</span>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" @click="showModal = false">Close</button>
											<button type="button" class="btn btn-primary" @click="addModule">Add Module</button>
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
import Vue from 'vue';
import draggable from "vuedraggable";
import Treeselect from '@riophae/vue-treeselect';
import '@riophae/vue-treeselect/dist/vue-treeselect.css';
import Toasted from 'vue-toasted';

Vue.use(Toasted);

export default {
		mounted() {
		console.log("Component mounted.");
	},

	components: {
		draggable,
		Treeselect
	},
	data() {
		return {
				baseUrl: document.head.querySelector('meta[name="base-url"]').content,
				data2: [],
				showModal: false,
				parent_module: null,
				module_name: null,
				is_root_module: false,
				div_index: 1,
				divs: [],
				chk_module: [],
				errors: {
					parent_module_err: false,
					module_name_err: false
				},
				active_id: [],
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
		document.getElementById("mnuModules").classList.add("active");
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

		addModule: async function () {
				this.errors.parent_module_err = false;
				this.errors.module_name_err = false;
				if(!this.is_root_module) {
					if(!this.parent_module || this.parent_module=="") {
						this.errors.parent_module_err = true;
						this.$toasted.error('Please select parent module').goAway(3000);
					}
				}
				if(!this.module_name || this.module_name=="") {
					this.errors.module_name_err = true;
					this.$toasted.error('Please enter module name').goAway(3000);
				}
				if(!this.errors.parent_module_err && !this.errors.module_name_err) {
						var parent_id = this.parent_module;
						if(this.is_root_module)
							parent_id = 0;
						let body = {
									'module_name': this.module_name,
									'parent_id': parent_id
						}
						let promise = axios.post(this.baseUrl + "/module/save", body);
						promise.then((response) => {
										if (response) {
										if (response.data.success) {
											this.getModuleList();
											this.is_root_module = false;
											this.showModal = false;
											this.module_name = null;
											this.div_index = 1;
											this.divs = [];
											this.$toasted.success(response.data.message).goAway(3000);
												// this.module_list.push(response.data.module);
										} else {
											this.$toasted.error(response.data.message).goAway(3000);
										}
										}
								});
								promise.catch((error) => {
									this.$toasted.error('Somethins went wrong. Please try again.').goAway(3000);
						});
				}
		},

		deleteModules: async function () {
			if(this.chk_module.length>0) {
				if(confirm("Do you really want to delete? This will delete all its children modules and test cases.")){
					let body = {
						'ids': this.chk_module
					}
					let promise = axios.post(this.baseUrl + "/modules/delete", body);
					promise.then((response) => {
									if (response) {
									if (response.data.success) {
											this.getModuleList();
											this.$toasted.success(response.data.message).goAway(3000);
									} else {
											this.$toasted.error(response.data.message).goAway(3000);
									}
									}
							});
							promise.catch((error) => {
								this.$toasted.error('Somethins went wrong. Please try again.').goAway(3000);
					});
				}
			}
			else
				this.$toasted.error('Please select at least 1 module to delete.').goAway(3000);
		},

		detailsModule: async function (module_val, index) {
			// this.active_id.push(module_val.id);
			// this.data2[index]['active'] = true;
				let body = {
						'id': module_val.id
				}
				let promise = axios.get(this.baseUrl + "/module/details/" + module_val.id);
				promise.then((response) => {
								if (response) {
								if (response.data.success) {
										// this.module_list.splice(index, 1);
										if(index==0) {
										 this.divs = [];
										 this.div_index = 1;
										}
										else {
											console.log(index);
											this.divs.splice(index);
											this.div_index = index+1;
										}
										if(response.data.module.length>0) {
											this.divs.push({
												id: this.div_index,
												name: module_val.text,
												data: response.data.module
											});
											this.div_index++;
										}
								} else {
									this.$toasted.error(response.data.message).goAway(3000);
								}
								}
						});
						promise.catch((error) => {
							this.$toasted.error('Somethins went wrong. Please try again.').goAway(3000);
				});
		},

		afterAdd(evt) {
			// console.log(evt.draggedContext)
			// const element = evt.moved.element
			// const oldIndex = evt.moved.oldIndex
			// const newIndex = evt.moved.newIndex
			// console.log(element)
			// console.log(oldIndex)
			// console.log(newIndex)
		},

		end (event) {
			console.log('end', event);
			// console.log('from', event.from.offsetParent.className);
			// console.log('to', event.to.offsetParent.className);
			// var from_div = event.from.children[0].id;
			// var to_div = event.from.children[0].id;

			var current_item_div = event.clone.id;
			var current_item = current_item_div.split("-");
			var module_id = current_item[2];
			// console.log(module_id);
			var from_div = event.from;
			var from_div_id = from_div.parentNode.id;
			// console.log(from_div_id);
			var to_div = event.to;
			var to_div_id = to_div.parentNode.id;
			// console.log(to_div_id);

			if(module_id==to_div_id) {
				this.$toasted.error('Active parent can not be moved to to it children.').goAway(5000);
				this.getModuleList();
				this.div_index = 1;
				this.divs = [];
			}
			else {
				let body = {
					'id': module_id,
					'parent_id': to_div_id,
				}
				let promise = axios.post(this.baseUrl + "/module/update", body);
				promise.then((response) => {
								if (response) {
								if (response.data.success) {
									this.$toasted.success(response.data.message).goAway(3000);	
								} else {
									this.$toasted.error(response.data.message).goAway(3000);
								}
								}
						});
						promise.catch((error) => {
							this.$toasted.error('Somethins went wrong. Please try again.').goAway(3000);
				});
			}
		},

		checkMove: function(evt){
			// console.log('draggedContext',evt.draggedContext);
			// console.log('relatedContext',evt.relatedContext);
			// console.log(evt.relatedContext.element.parent_id);

			// console.log(evt.draggedContext.element.parent_id);
			// console.log(evt.relatedContext.element.parent_id);
			// if(evt.draggedContext.element.parent_id==evt.relatedContext.element.parent_id) {
			// 	console.log('stop it');
			// 	return false;
			// }
			// else
			// 	return true;

			// let body = {
			// 	'id': evt.draggedContext.element.id,
			// 	'parent_id': evt.relatedContext.element.parent_id,
			// }
			// let promise = axios.post(this.baseUrl + "/module/update", body);
			// promise.then((response) => {
			// 				if (response) {
			// 				if (response.data.success) {
			// 					this.$toasted.success(response.data.message).goAway(3000);	
			// 				} else {
			// 					this.$toasted.error(response.data.message).goAway(3000);
			// 				}
			// 				}
			// 		});
			// 		promise.catch((error) => {
			// 			this.$toasted.error('Somethins went wrong. Please try again.').goAway(3000);
			// });
		}
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

.panel-body-grey {
	background: #EEEEEE;
}

.card {
	background: #FFFFFF;
	border-radius: 5px;
	padding: 10px;
	margin-bottom: 10px;
}
</style>
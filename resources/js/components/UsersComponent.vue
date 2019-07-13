<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title mb-0">
                            Manage Users
                        </div>

                        <div class="card-tools">
                            <button class="btn btn-primary" @click="createModal"><i class="fas fa-user-plus"></i> Add User</button>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in users.data" :key="user.id">
                                <td>{{ user.id }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td><span class="badge bg-success">{{ user.type | capitalize }}</span></td>
                                <td>{{ user.created_at | dateFormat }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" @click="editModal(user)"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-danger btn-sm" @click="deleteUser(user.id)"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <pagination :data="users" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editmode" class="modal-title"><i class="fas fa-user-plus"></i> Add User</h5>
                        <h5 v-show="editmode" class="modal-title"><i class="fas fa-user-edit"></i> Update User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input v-model="form.name" type="text" name="name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input v-model="form.email" type="email" name="email"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Bio</label>
                                <textarea v-model="form.bio" name="bio"
                                          class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                <has-error :form="form" field="bio"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Type</label>
                                <select v-model="form.type" name="type"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                    <option value="" selected>Select One</option>
                                    <option value="admin">Admin</option>
                                    <option value="author">Author</option>
                                    <option value="user">User</option>
                                </select>
                                <has-error :form="form" field="type"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input v-model="form.password" type="password" name="password"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                            <button v-show="!editmode" type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Save</button>
                            <button v-show="editmode" type="submit" class="btn btn-success"><i class="fas fa-edit"></i> Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data(){
            return {
                users : {},
                editmode: false,
                form : new Form({
                    id: '',
                    name : '',
                    email : '',
                    password : '',
                    type : '',
                    bio : '',
                    photo : '',
                })
            }
        },
        methods : {
            getResults(page = 1) {
                axios.get('api/users?page=' + page)
                    .then(response => {
                        this.users = response.data;
                    });
            },
            updateUser(){
                this.$Progress.start();
                this.form.put("api/users/" + this.form.id)
                    .then(() => {
                        $('#userModal').modal('hide');
                        this.$swal("Updated!", "Your file has been updated.", "success");
                        Fire.$emit("afterAction");
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$swal("Failed!", "There was something wrong.", "warning");
                        this.$Progress.fail();
                    });
            },
            editModal(user) {
                this.editmode = true;
                this.form.reset();
                $("#userModal").modal("show");
                this.form.fill(user);
            },
            createModal() {
                this.editmode = false;
                this.form.reset();
                $("#userModal").modal("show");
            },
            loadUsers(){
                axios.get('api/users')
                    .then(response => {
                        this.users = response.data;
                    })
                    .catch()
            },
            deleteUser(id) {
                this.$swal({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then(result => {
                    if (result.value) {
                        this.form
                            .delete("api/users/" + id)
                            .then(() => {
                                this.$swal("Deleted!", "Your file has been deleted.", "success");
                                Fire.$emit("afterAction");
                            })
                            .catch(() => {
                                this.$swal({
                                    type: "error",
                                    title: "Oops...",
                                    text: "Something went wrong!"
                                });
                            });
                    }
                });
            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/users')
                    .then(() => {
                        Fire.$emit('afterAction');
                        $("#userModal").modal("hide");
                        this.$swal({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            type: "success",
                            title: "User created successfully"
                        });
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
            }
        },
        created() {
            this.loadUsers();
            Fire.$on('afterAction', () => {
                this.loadUsers()
            });
            // call this function after each 3 seconds
            // setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>

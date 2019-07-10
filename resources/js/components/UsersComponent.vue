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
                            <button class="btn btn-primary" data-toggle="modal" data-target="#userModal"><i class="fas fa-user-plus"></i> Add User</button>
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
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1.</td>
                                <td>Shahid Nawaz</td>
                                <td>msnawazbd@gmail.com</td>
                                <td><span class="badge bg-success">Admin</span></td>
                                <td>
                                    <button class="btn btn-info btn-sm"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalTitle"><i class="fas fa-user-plus"></i> Add User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                        <button type="button" class="btn btn-success"><i class="fas fa-plus"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

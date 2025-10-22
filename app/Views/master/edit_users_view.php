<!--Learn Sections-->
<section id="learn" class="p-5">
        <div class="container">
            <div class="row pt-5">
                <div class="col text-center">
                    <h4>Edit Users</h4>
                    <?php
                    if($msg != "") {
                        echo '<p class="text-danger">' . $msg . '</p>';
                    }
                    ?>
                </div>
            </div>
            <div class="row px-5">
                <div class="col">
                <table class="table table-striped table-bordered text-left">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Activate/Deactivate</th>
                        <th>Authorize/Unauthorize</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($users as $user) {?>
                    <tr>
                        <td><a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#editUser<?php echo $user['id']; ?>"><?php echo $user['fname'] . ' ' . $user['lname']; ?></a>
                        <?php include 'modal_update_user.php'; ?></td>
                        <td><a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#resetUsr<?php echo $user['id']; ?>"><?php echo $user['username']; ?></a>
                            <?php include 'mod_reset_user.php'; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['type_desc']; ?></td>
                        <td class="text-center">
                            <?php
                            if($user['active'] == 1) { ?>
                            <a href="<?php echo base_url() . 'index.php/deactivate/' . $user['id'];  ?>" class="text-decoration-none">Deactivate</a>
                            <?php
                            }
                            else { ?>
                            <a href="<?php echo base_url() . 'index.php/activate/' . $user['id'];  ?>" class="text-decoration-none">Activate</a>
                            <?php
                            }
                            ?>
                        </td>
                        <td class="text-center">
                        <?php
                        if($user['authorized'] == 1) { ?>
                            <a href="<?php echo base_url() . 'index.php/unauthorize/' . $user['id'];  ?>" class="text-decoration-none">Unauthorize</a>
                        <?php
                        }
                        else { ?>
                            <a href="<?php echo base_url() . 'index.php/authorize/' . $user['id'];  ?>" class="text-decoration-none">Authorize</a>
                        <?php
                        }
                        ?>
                        </td>
                        <td class="text-center"><a href="#" data-bs-toggle="modal" data-bs-target="#delUsr<?php echo $user['id']; ?>"><i class="bi bi-trash"></i></a>
                        <?php include 'mod_del_user.php'; ?></td>
                    </tr>
                    <?php }?>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
    </section>

    <section id="learn" class="p-5 bg-light text-light">
        <div class="container">
            <div class="row align-items-center justify-content-between">

            </div>
        </div>
    </section>

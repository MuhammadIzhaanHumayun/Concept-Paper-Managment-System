<h2>
    Approval Inbox
</h2>


<div class="card">

    <div class="card-body">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>Project ID</th>

                    <th>Title</th>

                    <th>Category</th>

                    <th>Priority</th>

                    <th>Current Step</th>

                    <th>Action</th>

                </tr>

            </thead>


            <tbody>


                <?php foreach ($approvals as $approval): ?>

                    <tr>

                        <td>
                            <?= e($approval["project_id"]) ?>
                        </td>


                        <td>
                            <?= e($approval["project_title"]) ?>
                        </td>


                        <td>
                            <?= e($approval["category_name"]) ?>
                        </td>


                        <td>
                            <?= e($approval["priority"]) ?>
                        </td>


                        <td>
                            <?= e($approval["step_name"]) ?>
                            <?php if (!empty($approval['returned_for_revision'])): ?>
                                <span class="badge bg-warning text-dark ms-1">Returned for Revision</span>
                            <?php endif; ?>
                        </td>


                        <td>

                            <a
                                href="<?= site_url('approvals/review/'.$approval["db_project_id"].'/'.$approval["approval_id"]) ?>"
                                class="btn btn-primary btn-sm">
                                Review
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>


                <?php if (!$approvals): ?>

                    <tr>

                        <td
                            colspan="6"
                            class="text-center">
                            No pending approvals.
                        </td>

                    </tr>

                <?php endif; ?>


            </tbody>

        </table>

    </div>

</div>

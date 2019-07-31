@extends('layouts.app')

@section('pageHeader')
    <span class="uk-text-large">Teachers</span>
@endsection

@section('content')
<button class="uk-button uk-button-primary" id="add-teacher" uk-toggle="target: #create-modal"><span uk-icon="icon: plus"></span> Add Teacher</button>
<hr>
<table class="uk-table uk-table-divider">
    <thead>
        <tr>
            <th>Username</th>
            <th>Full Name</th>
            <th>Skype</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($teachers->isEmpty()): ?>
            <tr>
                <td colspan="4"><em>No Teachers Available</em></td>
            </tr>
        <?php else: ?>
            <?php foreach ($teachers as $teacher): ?>
                <tr>
                    <td><?php echo $teacher->username ?></td>
                    <td><?php echo $teacher->full_name ?></td>
                    <td><?php echo $teacher->skype ?></td>
                    <td>
                        Edit | Delete
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div id="create-modal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Create Teacher</h2>

        <form class="uk-form-stacked">
            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Username</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Password</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="password">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Password Repeat</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="password_repeat">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Full Name</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">E-Mail</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="email">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Skype</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Address</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Educational Attainment</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Birthday</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>
        </form>

        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" type="button">Save</button>
        </p>
    </div>
</div>
@endsection
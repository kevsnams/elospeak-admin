@extends('layouts.app')

@section('pageHeader')
    <span class="uk-text-large">Classrooms</span>
@endsection

@section('content')
<button class="uk-button uk-button-primary" id="add-classroom" uk-toggle="target: #create-modal"><span uk-icon="icon: plus"></span> Add Classroom</button>
<hr>
<table class="uk-table uk-table-divider">
    <thead>
        <tr>
            <th>ID</th>
            <th>Teacher</th>
            <th>Student</th>
            <th>Start</th>
            <th>End</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($classrooms->isEmpty()): ?>
            <tr>
                <td colspan="7"><em>No Classrooms Available</em></td>
            </tr>
        <?php else: ?>
            <?php foreach ($classrooms as $classroom): ?>
                <tr>
                    <td><?php echo $classroom->id ?></td>
                    <td>{ TEACHER_NAME }</td>
                    <td>{ STUDENT_NAME }</td>
                    <td><?php echo $classroom->start ?></td>
                    <td><?php echo $classroom->end ?></td>
                    <td><i>Pending</i></td>
                    <td>
                        Modify | Delete
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div id="create-modal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Create Classroom</h2>

        <form class="uk-form-stacked">
            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Teacher</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Student</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Class Start Date/Time</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Class End Date/Time</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="form-stacked-text" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Status</label>
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
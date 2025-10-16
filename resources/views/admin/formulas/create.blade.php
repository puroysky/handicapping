@extends('layouts.app')
@section('title', 'Create Formula')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Create Formula</h2>
    <form method="POST" action="">
        @csrf
        <div class="mb-3">
            <label for="formula_name" class="form-label">Formula Name</label>
            <input type="text" class="form-control" id="formula_name" name="formula_name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Variables</label>
            <div id="variables-list">
                <div class="row mb-2 variable-row">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="variables[0][name]" placeholder="Variable Name" required>
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="variables[0][value]" placeholder="Value" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-variable">Remove</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success" id="add-variable">Add Variable</button>
        </div>
        <div class="mb-3">
            <label for="expression" class="form-label">Expression</label>
            <textarea class="form-control" id="expression" name="expression" rows="3" placeholder="e.g. (score - par) * slope" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save Formula</button>
    </form>
</div>
<script>
    let variableIndex = 1;
    document.getElementById('add-variable').addEventListener('click', function() {
        const list = document.getElementById('variables-list');
        const row = document.createElement('div');
        row.className = 'row mb-2 variable-row';
        row.innerHTML = `<div class="col-md-5">
            <input type="text" class="form-control" name="variables[${variableIndex}][name]" placeholder="Variable Name" required>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="variables[${variableIndex}][value]" placeholder="Value" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-variable">Remove</button>
        </div>`;
        list.appendChild(row);
        variableIndex++;
    });
    document.getElementById('variables-list').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variable')) {
            e.target.closest('.variable-row').remove();
        }
    });
</script>
@endsection
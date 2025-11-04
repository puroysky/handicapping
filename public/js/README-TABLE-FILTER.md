# Reusable Table Filter Component - Server-Side with Client Rendering

## Overview

The `table-filter.js` provides a reusable, dynamic filtering system for HTML tables with **server-side processing and client-side rendering**. It sends filter criteria to the server, receives JSON data, and renders the results on the client side using a custom render function.

## Features

-   ✅ Server-side filtering for large datasets
-   ✅ Client-side rendering with custom render function
-   ✅ Dynamic filter modal generation
-   ✅ Multiple filter support (AND logic)
-   ✅ Different input types: text, number, date, select/dropdown
-   ✅ Auto-populated dropdowns from table data
-   ✅ Active filter display with individual remove buttons
-   ✅ Filter count badge on button
-   ✅ Loading spinner during server requests
-   ✅ Bootstrap 5 compatible
-   ✅ Easy to integrate across different views

## Installation

### 1. Include the Script

Add the script tag in your Blade template:

```blade
@section('content')
<!-- Include Reusable Table Filter Script -->
<script src="{{ asset('js/table-filter.js') }}"></script>

<div class="container-fluid py-0">
    <!-- Your content here -->
</div>
```

### 2. Add Filter Button

Add a filter button with the proper ID in your search section:

```html
<button
    class="btn btn-outline-secondary btn-modern d-flex align-items-center"
    id="filterButton"
    title="Filter"
>
    <i class="fas fa-filter me-2"></i>Filter
    <span
        class="badge bg-primary ms-2"
        id="activeFilterCount"
        style="display: none;"
        >0</span
    >
</button>
```

### 3. Initialize the Filter

Add initialization code in your JavaScript section:

```javascript
document.addEventListener("DOMContentLoaded", function () {
    tableFilterInstance = new TableFilter({
        tableBodyId: "mainTableBody",
        filterButtonId: "filterButton",
        showingCountId: "showing-count",
        totalCountId: "total-count",
        modalId: "filterModal",
        filterUrl: "{{ route('admin.scores.filter') }}", // Server endpoint
        csrfToken: "{{ csrf_token() }}", // CSRF token
        renderRow: function (score) {
            // Custom function to render each row
            return `
                <tr class="table-row">
                    <td><span class="fw-bold">${
                        score.player_name || "N/A"
                    }</span></td>
                    <td><span>${score.whs_no || "N/A"}</span></td>
                    <td><span>${score.account_no || "N/A"}</span></td>
                    <td><span>${score.tournament || "N/A"}</span></td>
                    <td><span class="badge bg-secondary">${
                        score.course || "N/A"
                    }</span></td>
                    <td><span class="fw-bold">${
                        score.adjusted_score || "N/A"
                    }</span></td>
                </tr>
            `;
        },
        fields: [
            {
                value: "player_name",
                label: "Player Name",
                type: "text",
                selector: ".player-name-cell span",
            },
            {
                value: "date_played",
                label: "Date Played",
                type: "date",
                selector: ".date-cell .cell-text-date",
            },
            {
                value: "tournament",
                label: "Tournament",
                type: "select",
                selector: ".tournament-cell span",
            },
            {
                value: "adjusted_score",
                label: "Adjusted Score",
                type: "number",
                selector: ".score-cell span",
            },
        ],
    });
});
```

## Configuration Options

### TableFilter Constructor Parameters

| Parameter        | Type     | Required | Default           | Description                                          |
| ---------------- | -------- | -------- | ----------------- | ---------------------------------------------------- |
| `tableBodyId`    | string   | No       | `'mainTableBody'` | ID of the table body to update with filtered results |
| `filterButtonId` | string   | No       | `'filterButton'`  | ID of the button that opens the filter modal         |
| `showingCountId` | string   | No       | `'showing-count'` | ID of element displaying current visible row count   |
| `totalCountId`   | string   | No       | `'total-count'`   | ID of element displaying total row count             |
| `modalId`        | string   | No       | `'filterModal'`   | ID for the generated filter modal                    |
| `filterUrl`      | string   | **Yes**  | -                 | Server endpoint URL for filter processing            |
| `csrfToken`      | string   | **Yes**  | -                 | Laravel CSRF token for POST requests                 |
| `renderRow`      | function | **Yes**  | -                 | Function to render each row from server data         |
| `fields`         | array    | **Yes**  | `[]`              | Array of field configuration objects                 |

### Field Configuration Object

Each field in the `fields` array should have:

| Property   | Type   | Required | Description                                               |
| ---------- | ------ | -------- | --------------------------------------------------------- |
| `value`    | string | Yes      | Field name to send to server (database column name)       |
| `label`    | string | Yes      | Display label shown in the filter dropdown                |
| `type`     | string | Yes      | Input type: `'text'`, `'number'`, `'date'`, or `'select'` |
| `selector` | string | Yes      | CSS selector to populate dropdown options (type='select') |

### Field Types

#### 1. Text (`type: 'text'`)

Standard text input for partial text matching.

```javascript
{ value: 'player_name', label: 'Player Name', type: 'text', selector: '.player-name-cell span' }
```

**Note:** The `selector` is only used for auto-populating select dropdowns. For text fields, it's kept for consistency but can be any valid selector.

#### 2. Number (`type: 'number'`)

Number input for numeric filtering.

```javascript
{ value: 'adjusted_score', label: 'Adjusted Score', type: 'number', selector: '.score-cell span' }
```

#### 3. Date (`type: 'date'`)

Date picker for date-based filtering.

```javascript
{ value: 'date_played', label: 'Date Played', type: 'date', selector: '.date-cell .cell-text-date' }
```

#### 4. Select (`type: 'select'`)

Dropdown auto-populated from existing table values. The `selector` is used to extract unique values from the table.

```javascript
{ value: 'tournament', label: 'Tournament', type: 'select', selector: '.tournament-cell span' }
```

## Server-Side Implementation

### 1. Expected Request Format

The client sends a POST request with the following JSON structure:

```json
{
    "filters": [
        {
            "field": "player_name",
            "value": "John",
            "type": "text"
        },
        {
            "field": "adjusted_score",
            "value": "72",
            "type": "number"
        },
        {
            "field": "date_played",
            "value": "2024-01-15",
            "type": "date"
        }
    ],
    "_token": "csrf_token_here"
}
```

### 2. Expected Response Format

Server should return JSON with the following structure:

```json
{
    "success": true,
    "data": [
        {
            "player_name": "John Doe",
            "whs_no": "12345",
            "account_no": "ACC001",
            "tournament": "Spring Open",
            "course": "Pine Valley",
            "adjusted_score": 72,
            "handicap_index": "12.5",
            "score_differential": "15.2",
            "date_played": "2024-01-15"
        }
    ],
    "count": 1,
    "total": 100
}
```

**Response Fields:**

-   `success`: Boolean indicating operation success
-   `data`: Array of objects to render (each object represents one table row)
-   `count`: Number of filtered results
-   `total`: Total records in database (before filtering)

### 3. Laravel Controller Example

```php
// app/Http/Controllers/Admin/ScoreController.php

public function filter(Request $request)
{
    try {
        $filters = $request->input('filters', []);
        $result = $this->scoreService->filter($filters);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'count' => $result['count'],
            'total' => $result['total']
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Filter failed: ' . $e->getMessage()
        ], 500);
    }
}
```

### 4. Laravel Service Example

```php
// app/Services/ScoreService.php

public function filter(array $filters): array
{
    $query = Score::query()
        ->with(['playerProfile.userProfile', 'tournament', 'tournamentCourse.course', 'tee']);

    foreach ($filters as $filter) {
        $field = $filter['field'];
        $value = $filter['value'];
        $type = $filter['type'];

        switch ($field) {
            case 'player_name':
                $query->whereHas('playerProfile.userProfile', function($q) use ($value) {
                    $q->where('name', 'LIKE', "%{$value}%");
                });
                break;

            case 'adjusted_score':
                $query->where('adjusted_gross_score', $value);
                break;

            case 'date_played':
                $query->whereDate('date_played', $value);
                break;

            case 'tournament':
                $query->whereHas('tournament', function($q) use ($value) {
                    $q->where('name', $value);
                });
                break;
        }
    }

    $total = Score::count();
    $filteredScores = $query->get();
    $count = $filteredScores->count();

    $data = $filteredScores->map(function($score) {
        return [
            'player_name' => $score->playerProfile->userProfile->name ?? 'N/A',
            'whs_no' => $score->playerProfile->whs_no ?? 'N/A',
            'account_no' => $score->playerProfile->account_no ?? 'N/A',
            'tournament' => $score->tournament->name ?? 'N/A',
            'course' => ($score->tournamentCourse->course->name ?? 'N/A') . ' - ' . ($score->tee->name ?? 'N/A'),
            'adjusted_score' => $score->adjusted_gross_score ?? 'N/A',
            'handicap_index' => number_format($score->tournament_handicap_index ?? 0, 1),
            'score_differential' => number_format($score->score_differential ?? 0, 1),
            'date_played' => $score->date_played ? $score->date_played->format('Y-m-d') : 'N/A',
            'score_id' => $score->id
        ];
    });

    return [
        'data' => $data->toArray(),
        'count' => $count,
        'total' => $total
    ];
}
```

### 5. Route Configuration

```php
// routes/web.php

Route::post('/admin/scores/filter', [ScoreController::class, 'filter'])
    ->name('admin.scores.filter');
```

## Complete Working Example

### scores.blade.php

```blade
@extends('layouts.app')

@section('content')
<!-- Include Reusable Table Filter Script -->
<script src="{{ asset('js/table-filter.js') }}"></script>

<div class="container-fluid">
    <!-- Search and Filter Section -->
    <div class="table-search-section">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex gap-2">
                    <div class="search-wrapper flex-grow-1">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" id="tableSearch" placeholder="Search...">
                    </div>
                    <button class="btn btn-outline-secondary" id="filterButton" title="Filter">
                        <i class="fas fa-filter me-2"></i>Filter
                        <span class="badge bg-primary ms-2" id="activeFilterCount" style="display: none;">0</span>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted">
                    Showing <span id="showing-count">{{ count($scores) }}</span>
                    of <span id="total-count">{{ count($scores) }}</span> records
                </small>
            </div>
        </div>
    </div>

    <!-- Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Player Name</th>
                <th>WHS No</th>
                <th>Account No</th>
                <th>Tournament</th>
                <th>Course</th>
                <th>Adjusted Score</th>
                <th>Handicap Index</th>
                <th>Score Differential</th>
                <th>Date Played</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="mainTableBody">
            @foreach($scores as $score)
            <tr class="table-row">
                <td class="player-name-cell">
                    <span class="fw-bold">{{ $score->playerProfile->userProfile->name ?? 'N/A' }}</span>
                </td>
                <td class="whs-cell">
                    <span>{{ $score->playerProfile->whs_no ?? 'N/A' }}</span>
                </td>
                <td class="account-cell">
                    <span>{{ $score->playerProfile->account_no ?? 'N/A' }}</span>
                </td>
                <td class="tournament-cell">
                    <span>{{ $score->tournament->name ?? 'N/A' }}</span>
                </td>
                <td class="course-cell">
                    <span class="badge bg-secondary me-1">{{ $score->tournamentCourse->course->name ?? 'N/A' }}</span>
                    <span class="badge bg-info">{{ $score->tee->name ?? 'N/A' }}</span>
                </td>
                <td class="score-cell">
                    <span class="fw-bold">{{ $score->adjusted_gross_score ?? 'N/A' }}</span>
                </td>
                <td class="handicap-cell">
                    <span>{{ number_format($score->tournament_handicap_index ?? 0, 1) }}</span>
                </td>
                <td class="differential-cell">
                    <span>{{ number_format($score->score_differential ?? 0, 1) }}</span>
                </td>
                <td class="date-cell">
                    <span class="cell-text-date">{{ $score->date_played ? $score->date_played->format('Y-m-d') : 'N/A' }}</span>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="viewScore({{ $score->id }})">View</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
let tableFilterInstance;

document.addEventListener('DOMContentLoaded', function() {
    tableFilterInstance = new TableFilter({
        tableBodyId: 'mainTableBody',
        filterButtonId: 'filterButton',
        showingCountId: 'showing-count',
        totalCountId: 'total-count',
        modalId: 'filterModal',
        filterUrl: '{{ route("admin.scores.filter") }}',
        csrfToken: '{{ csrf_token() }}',
        renderRow: function(score) {
            return `
                <tr class="table-row">
                    <td class="player-name-cell">
                        <span class="fw-bold">${score.player_name || 'N/A'}</span>
                    </td>
                    <td class="whs-cell">
                        <span>${score.whs_no || 'N/A'}</span>
                    </td>
                    <td class="account-cell">
                        <span>${score.account_no || 'N/A'}</span>
                    </td>
                    <td class="tournament-cell">
                        <span>${score.tournament || 'N/A'}</span>
                    </td>
                    <td class="course-cell">
                        <span class="badge bg-secondary">${score.course || 'N/A'}</span>
                    </td>
                    <td class="score-cell">
                        <span class="fw-bold">${score.adjusted_score || 'N/A'}</span>
                    </td>
                    <td class="handicap-cell">
                        <span>${score.handicap_index || '0.0'}</span>
                    </td>
                    <td class="differential-cell">
                        <span>${score.score_differential || '0.0'}</span>
                    </td>
                    <td class="date-cell">
                        <span class="cell-text-date">${score.date_played || 'N/A'}</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="viewScore(${score.score_id})">View</button>
                    </td>
                </tr>
            `;
        },
        fields: [
            {
                value: 'player_name',
                label: 'Player Name',
                type: 'text',
                selector: '.player-name-cell span'
            },
            {
                value: 'whs_no',
                label: 'WHS No',
                type: 'text',
                selector: '.whs-cell span'
            },
            {
                value: 'account_no',
                label: 'Account No',
                type: 'text',
                selector: '.account-cell span'
            },
            {
                value: 'tournament',
                label: 'Tournament',
                type: 'select',
                selector: '.tournament-cell span'
            },
            {
                value: 'adjusted_score',
                label: 'Adjusted Score',
                type: 'number',
                selector: '.score-cell span'
            },
            {
                value: 'date_played',
                label: 'Date Played',
                type: 'date',
                selector: '.date-cell .cell-text-date'
            }
        ]
    });
});

function viewScore(id) {
    window.location.href = `/admin/scores/${id}`;
}
</script>
@endsection
```

## API Methods

### Public Methods

#### `getActiveFilters()`

Returns array of currently active filters.

```javascript
const filters = tableFilterInstance.getActiveFilters();
console.log(filters);
// Output: [{ field: 'player_name', value: 'John', type: 'text' }]
```

#### `clearAllFilters()`

Clears all active filters and reloads data from server.

```javascript
tableFilterInstance.clearAllFilters();
```

## Architecture Overview

### Data Flow

1. **User adds filter** → Filter stored in `activeFilters` array
2. **User clicks Apply** → POST request sent to `filterUrl` with filters
3. **Server processes** → Query database and return JSON with `data` array
4. **Client receives** → Loop through `data` array calling `renderRow(item)`
5. **Table updates** → innerHTML replaced with rendered rows
6. **Counts update** → Show filtered count vs total count

### Client Responsibilities

-   Manage filter UI (modal, inputs, badges)
-   Send filter requests to server
-   Receive JSON response
-   Render HTML for each row using `renderRow` function
-   Update count displays
-   Show loading states
-   Handle errors

### Server Responsibilities

-   Receive filter criteria
-   Query database with filters
-   Return JSON with:
    -   `data`: Array of objects (one per row)
    -   `count`: Filtered results count
    -   `total`: Total records in database

## Styling

The filter component uses Bootstrap 5 classes. You can customize the modal appearance:

```css
/* Custom modal styling */
#filterModal .modal-dialog {
    max-width: 600px;
}

#filterModal .active-filters {
    max-height: 200px;
    overflow-y: auto;
}

/* Custom badge colors */
.filter-badge {
    background-color: #0d6efd;
}
```

## Error Handling

The component includes built-in error handling:

1. **Network errors**: Shows alert, restores table content
2. **Server errors**: Displays error message from server
3. **Empty results**: Shows "No records found" message

```javascript
// Server error response example
{
    "success": false,
    "message": "Database connection failed"
}
```

## Best Practices

1. **renderRow Function**

    - Keep HTML generation efficient
    - Use template literals for clean code
    - Handle null/undefined values gracefully
    - Include data attributes for event handlers

2. **Server Response**

    - Return consistent structure
    - Include proper HTTP status codes
    - Provide meaningful error messages
    - Optimize queries for large datasets

3. **Filter Fields**

    - Use database column names for `value`
    - Provide clear `label` text
    - Match `type` to data type
    - Use correct `selector` for dropdowns

4. **Performance**
    - Add database indexes on filterable columns
    - Limit result sets on server
    - Use pagination for large datasets
    - Cache frequently-used queries

## Troubleshooting

### Filter not applying

**Problem:** Clicking Apply doesn't filter data

**Solutions:**

-   Check `filterUrl` points to correct route
-   Verify CSRF token is valid
-   Check browser console for errors
-   Verify server endpoint returns correct JSON structure

### Dropdown not populating

**Problem:** Select dropdown is empty

**Solutions:**

-   Verify `selector` matches table cell structure
-   Check table has data when modal opens
-   Ensure selector targets elements with text content

### Rows not rendering

**Problem:** Table shows "No records found" when data exists

**Solutions:**

-   Check `renderRow` function returns valid HTML string
-   Verify server returns `data` array (not `results` or other key)
-   Check for JavaScript errors in `renderRow` function
-   Ensure all data fields exist in server response

### Count not updating

**Problem:** "Showing X of Y" displays incorrect numbers

**Solutions:**

-   Verify server returns both `count` and `total` in response
-   Check `showingCountId` and `totalCountId` match element IDs
-   Ensure response structure matches expected format

## License

This component is provided as-is for use in your Laravel projects.

## Support

For issues or questions, refer to the implementation examples in this document or check the inline comments in `table-filter.js`.

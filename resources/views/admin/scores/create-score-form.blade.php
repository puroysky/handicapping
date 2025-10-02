@extends('layouts.app')

@section('content')
<h1 class="my-4">Create Score</h1>
<label for="">Mode</label>
<select name="player_id" id="player_id" class="form-select mb-3" required>
    <option value="hole_by_hole" disabled selected>Hole by Hole</option>
    <option value="1">Gross Score</option>
</select>
<table class="table table-bordered">
    <tr>
        <td>Hole</td>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        <td>8</td>
        <td>9</td>
        <td>10</td>
        <td>11</td>
        <td>12</td>
        <td>13</td>
        <td>14</td>
        <td>15</td>
        <td>16</td>
        <td>17</td>
        <td>18</td>
        <td>Total</td>
    </tr>

    <tr>
        <td>PAR</td>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        <td>8</td>
        <td>9</td>
        <td>10</td>
        <td>11</td>
        <td>12</td>
        <td>13</td>
        <td>14</td>
        <td>15</td>
        <td>16</td>
        <td>17</td>
        <td>18</td>
        <td>Total</td>
    </tr>
    <tr>
        <td>Score</td>
        <td><input type="number" name="score[1]" min="1" max="5" required></td>
        <td><input type="number" name="score[2]" min="1" max="5" required></td>
        <td><input type="number" name="score[3]" min="1" max="5" required></td>
        <td><input type="number" name="score[4]" min="1" max="5" required></td>
        <td><input type="number" name="score[5]" min="1" max="5" required></td>
        <td><input type="number" name="score[6]" min="1" max="5" required></td>
        <td><input type="number" name="score[7]" min="1" max="5" required></td>
        <td><input type="number" name="score[8]" min="1" max="5" required></td>
        <td><input type="number" name="score[9]" min="1" max="5" required></td>
        <td><input type="number" name="score[10]" min="1" max="5" required></td>
        <td><input type="number" name="score[11]" min="1" max="5" required></td>
        <td><input type="number" name="score[12]" min="1" max="5" required></td>
        <td><input type="number" name="score[13]" min="1" max="5" required></td>
        <td><input type="number" name="score[14]" min="1" max="5" required></td>
        <td><input type="number" name="score[15]" min="1" max="5" required></td>
        <td><input type="number" name="score[16]" min="1" max="5" required></td>
        <td><input type="number" name="score[17]" min="1" max="5" required></td>
        <td><input type="number" name="score[18]" min="1" max="5" required></td>
        <td><input type="number" name="score[total]" min="18" max="90" required></td>
    </tr>
</table>
@endsection
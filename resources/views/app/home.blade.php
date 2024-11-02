@extends('layouts.app')

@section('content')
<style>
  /* General styling */
  /* General styling */
  body {
    background-color: #e0f7df;
    font-family: 'Poppins', sans-serif;
  }

  .container {
    max-width: 800px;
    margin: auto;
    padding: 20px;
  }

  /* Header and Coin Section */
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .btn-coin {
    background-color: #4caf50;
    color: white;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
  }

  /* Daily Task Cards */
  .task-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 20px;
  }

  .task-card {
    background-color: #e9f8e7;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 10px;
    max-width: 300px;
    position: relative;
  }

  .task-card img {
    width: 40px;
    height: 40px;
  }

  .task-info {
    flex-grow: 1;
  }

  .task-info h4 {
    font-size: 1rem;
    font-weight: bold;
    margin: 0;
    color: #333;
  }

  .task-info h4 span {
    font-weight: normal;
    color: #666;
    font-size: 0.85rem;
  }

  .task-info p {
    margin: 5px 0;
    font-size: 0.85rem;
    color: #666;
  }

  .progress-bar {
    background-color: #d6eecd;
    border-radius: 10px;
    overflow: hidden;
    height: 8px;
    display: flex;
    position: relative;
    gap: 2px;
  }

  .progress {
    background-color: #4caf50;
    width: 70%;
    /* Adjust this width to reflect task progress */
    height: 100%;
    border-radius: 10px;
  }

  .status-icon {
    width: 20px;
    height: 20px;
    background-color: #e0e0e0;
    border-radius: 50%;
    margin-left: auto;
  }

  /* Program List Section */
  .program-list {
    margin-top: 30px;
    padding: 15px;
    background-color: #f1f9f1;
    border-radius: 10px;
  }

  .program-item {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .program-text {
    flex: 1;
    margin-right: 10px;
  }

  .btn-join {
    background-color: #ff6961;
    color: white;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
  }
</style>

<div class="card">
  <div class="card-body">

    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div>
            <h3>Hay, <span class="text-primary">{{ $auth->name }}</span></h3>
          </div>
          <div>
            <span class="badge bg-warning text-dark">Koin: <span id="totalCoins">{{ Auth::user()->coins }}</span></span>
            <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-between">
      <div>
        <h3>Kelola Todolist Kamu</h3>
      </div>
      <div class="text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTodo">Tambah Data</button>
        <a href="{{ route('shop.index') }}" class="btn btn-success">Shop</a>
      </div>
    </div>
    <hr />
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Aktivitas</th>
          <th scope="col">Progres</th>
          <th scope="col">Status</th>
          <th scope="col">Frekuensi</th>
          <th scope="col">Koin</th>
          <th scope="col">Tanggal Dibuat</th>
          <th scope="col">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        @if (isset($todos) && sizeof($todos) > 0)
          @php
      $counter = 1;
    @endphp
          @foreach ($todos as $todo)
        <tr>
        <td>{{ $counter++ }}</td>
        <td>
        <a href="{{ route('todo.show', $todo->id) }}">{{ $todo->activity }}</a> <!-- Link to detail -->
        </td>
        <td>{{ $todo->progress }}/{{ $todo->target }}</td>
        <td>
        @if ($todo->status)
      <span class="badge bg-success">Selesai</span>
    @else
    <span class="badge bg-danger">Belum Selesai</span>
  @endif
        </td>
        <td>{{ ucfirst($todo->frequency) }}</td>
        <td>{{ $todo->coins }}</td>
        <td>{{ date('d F Y - H:i', strtotime($todo->created_at)) }}</td>
        <td>
        <form action="{{ route('todo.increment-progress', $todo->id) }}" method="POST" style="display:inline;">
          @csrf
          <button type="submit" class="btn btn-sm btn-info">Tambah Progres</button>
        </form>
        <form action="{{ route('post.todo.delete') }}" method="POST" style="display:inline;">
          @csrf
          <input type="hidden" name="id" value="{{ $todo->id }}">
          <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
        </form>
        </td>
        </tr>
      @endforeach
    @else
    <tr>
      <td colspan="8" class="text-center text-muted">Belum ada data tersedia!</td>
    </tr>
  @endif
      </tbody>
    </table>

  </div>
</div>

<!-- MODAL ADD TODO -->
<div class="modal fade" id="addTodo" tabindex="-1" aria-labelledby="addTodoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTodoLabel">Tambah Data Todo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('post.todo.add') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="inputActivity" class="form-label">Aktivitas</label>
            <input type="text" name="activity" class="form-control" id="inputActivity"
              placeholder="Contoh: Belajar membuat aplikasi website sederhana">
          </div>
          <div class="mb-3">
            <label for="inputTarget" class="form-label">Target Progres</label>
            <input type="number" name="target" class="form-control" id="inputTarget"
              placeholder="Masukkan target progres, misal 8">
          </div>
          <div class="mb-3">
            <label for="inputFrequency" class="form-label">Frekuensi</label>
            <select name="frequency" class="form-select" id="inputFrequency">
              <option value="daily">Per Hari</option>
              <option value="weekly">Per Minggu</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
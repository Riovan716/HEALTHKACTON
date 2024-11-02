@extends('layouts.app')

@section('content')
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
        <a href="{{ route('todo.show', $todo->id) }}">{{ $todo->activity }}</a> <!-- Make it clickable -->
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


<!-- MODAL EDIT TODO -->
<div class="modal fade" id="editTodo" tabindex="-1" aria-labelledby="editTodoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTodoLabel">Ubah Data Todo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('post.todo.edit') }}" method="POST">
        @csrf
        <input name="id" type="hidden" id="inputEditTodoId">

        <div class="modal-body">
          <div class="mb-3">
            <label for="inputEditActivity" class="form-label">Aktivitas</label>
            <input type="text" name="activity" class="form-control" id="inputEditActivity"
              placeholder="Contoh: Belajar membuat aplikasi website sederhana">
          </div>

          <div class="mb-3">
            <label for="selectEditStatus" class="form-label">Status</label>
            <select class="form-select" name="status" id="selectEditStatus">
              <option value="0">Belum Selesai</option>
              <option value="1">Selesai</option>
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

<!-- MODAL DELETE TODO -->
<div class="modal fade" id="deleteTodo" tabindex="-1" aria-labelledby="deleteTodoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTodoLabel">Hapus Data Todo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          Kamu akan menghapus todo
          <strong class="text-danger" id="deleteTodoActivity"></strong>.
          Apakah kamu yakin?
        </div>
      </div>
      <div class="modal-footer">
        <form action="{{ route('post.todo.delete') }}" method="POST">
          @csrf
          <input name="id" type="hidden" id="inputDeleteTodoId">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Ya, Tetap Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('other-js') 
<script>
  function showModalEditTodo(todoId, activity, status) {
    const modalEditTodo = document.getElementById("editTodo");
    const inputId = document.getElementById("inputEditTodoId");
    const inputActivity = document.getElementById("inputEditActivity");
    const selectStatus = document.getElementById("selectEditStatus");

    inputId.value = todoId;
    inputActivity.value = activity;
    selectStatus.value = status;

    var myModal = new bootstrap.Modal(modalEditTodo)
    myModal.show()
  }

  function showModalDeleteTodo(todoId, activity) {
    const modalDeleteTodo = document.getElementById("deleteTodo");
    const elemntActivity = document.getElementById("deleteTodoActivity");
    const inputId = document.getElementById("inputDeleteTodoId");

    inputId.value = todoId;
    elemntActivity.innerText = activity;

    var myModal = new bootstrap.Modal(modalDeleteTodo)
    myModal.show()
  } 
</script>
@endsection
<!-- resources/views/products/index.blade.php -->
<!doctype html>
<html>
<head>
  <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>
<body>
  <div>
    <label>Filter by Category:</label>
    <select id="category-filter">
      <option value="">— All —</option>
      @foreach($categories as $cat)
        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
      @endforeach
    </select>
  </div>

  <table id="products-table" class="display" style="width:100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Category</th>
        <th>Price</th>
      </tr>
    </thead>
  </table>

  <script>
  $(function(){
    let table = $('#products-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '{{ route("products.data") }}',
        data: d => d.category_id = $('#category-filter').val()
      },
      columns: [
        { data: 'id' },
        { data: 'name' },
        // note: use the addColumn alias in controller
        { data: 'category_name', name: 'category.name' },
        { data: 'price' },
      ]
    });

    $('#category-filter').on('change', ()=> table.ajax.reload());
  });
  </script>
</body>
</html>

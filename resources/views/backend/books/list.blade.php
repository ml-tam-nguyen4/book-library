@extends('backend.layouts.main')
@section('title',__('books.title_book'))
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="box">
                    <div class="box-header">
                        <!-- add form search and select for book -->
                        <!-- start -->
                        <form action="{{ url('admin/books/search') }}" method="GET" id="frm-search">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <span class="h3 text-uppercase">{{ __('books.list_book') }}</span>
                                </div>
                                <div class="form-group col-md-5">
                                    <input type="text" class="form-control" id="search" name="search" value="{{ old('search') }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <select id="select_search" class="form-control" name="searchBy" value="{{ old('searchBy') }}">
                                        <option selected>{{ __('general.all') }}</option>
                                        <option>{{ __('books.author') }}</option>
                                        <option>{{ __('books.name') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <button type="submit" class="btn btn-default" ><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                       <!-- end -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-md-10">
            <div class="box">
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                      <tr>
                          <th>{{ __('books.numbers_order') }}</th>
                          <th>{{ __('books.name') }}</th>
                          <th>{{ __('books.author') }}</th>
                          <th>{{ __('books.average_review_score') }}</th>
                          <th>{{ __('books.total_borrow') }}</th>
                          <th></th>
                      </tr>
                      @foreach ($books as $book)
                          <tr>
                              <td>{{ $book->id }}</td>
                              <td>{{ $book->name }}</td>
                              <td>{{ $book->author }}</td>
                              <td>{{ $book->avg_rating }}</td>
                              <td>{{ $book->total_borrow }}</td>
                              <td align="center">
                                  <a href="{{ route('books.edit', $book->) }}"
                                     class= "btn-edit fa fa-pencil-square-o btn-custom-option pull-left-center"></a>
                              </td>
                          </tr>
                      @endforeach
                  </table>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
        </div>
        {{ $books->links() }}
    </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
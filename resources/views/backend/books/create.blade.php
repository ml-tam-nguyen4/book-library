@extends('backend.layouts.main')
@section('title', __('book.create_book'))
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ __('book.create_book') }}
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('home.index') }}"><i class="fa fa-book"></i>{{ __('book.admin') }}</a></li>
      <li><a href="{{ route('books.index') }}">{{ __('book.book') }}</a></li>
      <li class="active">{{ __('book.create') }}</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <!-- show message response -->
        @include('flash::message')
        <div class="box box-info">
          <!-- /.box-header -->
          <div class="box-body pad">
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
              {{csrf_field()}}
              <div class="form-group">
                <label for="name">Name</label>
                <input name="name" type="text" class="form-control" id="name" placeholder="{{ __('book.enter_name') }}" value="{{ old('name') }}">
                @if($errors->first('name'))
                  <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
              <div class="form-group col-sm-3 inline-field">
                <label for="name">{{ __('book.category') }}</label></br>
                <select name="category_id" id="category" class="form-control">
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ (old('category_id') == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
                </select>
              </div>
              <div class="form-group col-sm-3 inline-field">
                <label for="author">{{ __('book.author') }}</label>
                <input name="author" type="text" class="form-control" id="author" placeholder="{{ __('book.enter_author') }}" value="{{ old('author') }}">
                @if($errors->first('author'))
                  <span class="text-danger">{{ $errors->first('author') }}</span>
                @endif
              </div>
              <div class="form-group col-sm-3 inline-field">
                <label for="year">{{ __('book.year') }}</label>
                <input name="year" type="text" class="form-control date-own" id="year" placeholder="{{ __('book.enter_year') }}" value="{{ old('year') }}">
                @if($errors->first('year'))
                  <span class="text-danger">{{ $errors->first('year') }}</span>
                @endif
              </div>
              <div class="form-group col-sm-3 inline-field">
                <label for="price">{{ __('book.price') }}</label>
                <input name="price" type="text" class="form-control" id="price" placeholder="{{ __('book.enter_price') }}" value="{{ old('price') }}">
                @if($errors->first('price'))
                  <span class="text-danger">{{ $errors->first('price') }}</span>
                @endif
              </div>
              <div class="form-group">
                <label for="employee_code">{{ __('book.donator') }}</label>
                <input name="employee_code" type="text" class="form-control" id="employee_code" placeholder="{{ __('book.enter_employee_code') }}" value="{{ old('employee_code') }}" >
                @if($errors->first('employee_code'))
                  <span class="text-danger">{{ $errors->first('employee_code') }}</span>
                @endif
              </div>
              <div class="form-group col-sm-6 inline-field">
                <label for="pages">{{ __('book.pages') }}</label>
                <input name="pages" type="text" class="form-control" id="pages" placeholder="{{ __('book.enter_pages') }}" value="{{ old('pages') }}" >
                @if($errors->first('pages'))
                  <span class="text-danger">{{ $errors->first('pages') }}</span>
                @endif
              </div>
              <div class="form-group col-sm-6 inline-field">
                <label for="language">{{ __('book.language') }}</label>
                <select name="language" id="language" class="form-control">
                  @foreach(config('define.languages') as $key => $value)
                    <option value="{{ $key }}" {{ (old('language') == $key) ? 'selected' : '' }}>{{ $value }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="description">{{__('book.description') }}</label></br>
                <textarea name="description" class="ckeditor" id="description" placeholder="{{ __('book.description') }}">{{ old('description') }}</textarea>
                @if($errors->first('description'))
                  <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
              </div>
              <div class="form-group">
                <label for="exampleInputFile">{{ __('book.image') }}</label>
                <input id="image" name="image" type="file" id="exampleInputFile">
                @if($errors->first('image'))
                  <span class="text-danger">{{ $errors->first('image') }}</span>
                @endif
              </div>
              <img id="image-display" width="150" height="200">

              <div class="box-footer">
                <button id="btn-add-book" type="submit" class="btn btn-primary">{{ __('book.submit') }}</button>
                <button id="btn-reset" type="reset" class="btn btn-danger">{{ __('book.reset') }}</button>
                <a id="btn-back" href="{{ route('books.index') }}" class="btn btn-default">{{ __('book.back') }}</a>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /.box -->
    </div>
    <!-- ./row -->
  </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

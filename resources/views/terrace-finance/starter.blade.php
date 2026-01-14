@extends('layouts.terrace-finance.app')

@section('title', 'Starter')
@section('page_title', 'Starter Page')

@section('page_header')
    <x-terrace-finance.page-header
        title="Starter Page"
        :breadcrumbs="[
            ['label' => 'Pages'],
            ['label' => 'Starter'],
        ]"
    />
@endsection

@section('content')
    <div class="page-category">Inner page content goes here.</div>
@endsection

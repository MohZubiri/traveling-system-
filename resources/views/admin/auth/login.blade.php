@extends('layouts.guest')

@section('content')
<div class="flex-fill d-flex flex-column justify-content-center" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
     <div class="container-tight py-6">
       <div class="text-center mb-4">
         <img src="{{asset('static/logo.svg')}}" height="36" alt="">
       </div>
       <form class="card card-md"  method="POST" action="{{ route('admin.login') }}">
       @csrf
         <div class="card-body">
           <h2 class="mb-5 text-center">{{ __('messages.Login_to_your_account') }}</h2>
           <div class="mb-3">
             <label class="form-label">{{ __('messages.email') }}</label>
             <input type="email" class="form-control" name='email' placeholder="{{ __('messages.enter_email') }}" autocomplete="off">
           </div>
           <div class="mb-2">
             <label class="form-label">
               {{ __('messages.password') }}
               <span class="form-label-description">
                 <a href="{{ route('admin.password.request') }}"
                 class="link-secondary"
                 title="{{ __('messages.forgot_password') }}" data-toggle="tooltip"
                 data-original-title="{{ __('messages.forgot_password') }}">{{ __('messages.forgot_password') }}</a>
               </span>
             </label>
             <div class="input-group input-group-flat">
               <input type="password" class="form-control" name='password' placeholder="{{ __('messages.password') }}" >
               <span class="input-group-text">
                 <a href="#" class="link-secondary" title="{{ __('messages.show_password') }}" data-toggle="tooltip">
                   <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><circle cx="12" cy="12" r="2" /><path d="M2 12l1.5 2a11 11 0 0 0 17 0l1.5 -2" /><path d="M2 12l1.5 -2a11 11 0 0 1 17 0l1.5 2" /></svg>
                 </a>
               </span>
             </div>
           </div>
           <div class="mb-2">
             <label class="form-check">
               <input type="checkbox" class="form-check-input"/>
               <span class="form-check-label">{{ __('messages.remember_me') }}</span>
             </label>
           </div>
           <div class="form-footer">
             <button type="submit" class="btn btn-primary btn-block">{{ __('messages.sign_in') }}</button>
           </div>
         </div>
       </form>
     </div>
   </div>
@endsection

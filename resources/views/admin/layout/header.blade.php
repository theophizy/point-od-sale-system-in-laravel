<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{env('SYSTEM_NAME')}}</title>
    @include('admin.layout.css-links')
    <script type="Text/javascript">
setTimeout(function(){
    $('#msgdiv').fadeOut('slow');
    
}, 6000);

</script>
<!-- <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script> -->

</head>


    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <!-- <a class="navbar-brand brand-logo" href="index.html"><img src="{{asset('admin/images/logo.svg')}}" alt="logo"/></a> -->
          <a class="navbar-brand brand-logo-mini" href="{{route('dashboard')}}"><img src="{{asset('admin/images/logo-mini.svg')}}" alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center d-none d-lg-flex" type="button" data-toggle="minimize">
            <span class="typcn typcn-th-menu"></span>
          </button>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
         
          <ul class="navbar-nav navbar-nav-right">
            
            <li class="nav-item dropdown d-flex">
              <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="{{route('product_lowQuantity')}}">
                <i class="typcn typcn-message-typing"></i>
                <span class="count bg-danger" title="Product with Quantity less than 20">{{$drugsLessThanTenInQuantity}}</span>
              </a>
           
            </li>
            <li class="nav-item dropdown  d-flex">
              <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="{{route('product_expiredOrExpiring')}}">
                <i class="typcn typcn-bell mr-0"></i>
                <span class="count bg-danger" title="No of Drugs Expired/Expiring in 30days">{{$getExpiringOrExpiredProducts}}</span>
              </a>
              
            </li>
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle  pl-0 pr-0" href="#" data-toggle="dropdown" id="profileDropdown">
                <i class="typcn typcn-user-outline mr-0"></i>
                <span class="nav-profile-name">{{Auth::guard('admin')->user()->name}}</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <!-- <a class="dropdown-item">
                <i class="typcn typcn-cog text-primary"></i>
                Settings
                </a> -->
                @if(Auth::guard('admin')->user()->id !=1)
                <a class="dropdown-item" href="{{route('user_profile')}}">
                <i class="typcn typcn-user text-primary"></i>
                Profile
                </a>
                @endif
                @if(Auth::guard('admin')->user()->id ==1)
                <a class="dropdown-item" href="{{route('user_masterAccounnt')}}">
                <i class="typcn typcn-user text-primary"></i>
                Modify Master Account
                </a>
                @endif
                <a class="dropdown-item" href="{{ route('user_changePasword')}}">
                <i class="typcn typcn-lock text-primary"></i>
                Change Password
                </a>
                <a class="dropdown-item" href="{{route('logout')}}">
                <i class="typcn typcn-power text-primary"></i>
                Logout
                </a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="typcn typcn-th-menu"></span>
          </button>
        </div>
      </nav>
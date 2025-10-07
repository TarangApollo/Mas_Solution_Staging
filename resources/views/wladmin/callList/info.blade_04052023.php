@extends('layouts.wladmin')

@section('title', 'Call Information')

@section('content')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Mas Solutions</title>
    <!-- Layout styles -->
    <link rel="stylesheet" href="../global/assets/css/style-admin.css" />
    <link rel="stylesheet" href="../global/assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../global/assets/fonts/mas-solution/styles.css" />
    <link rel="stylesheet" href="../global/assets/vendors/css/vendor.bundle.base.css">

    <!-- css for this page -->

    <link rel="shortcut icon" href="../global/assets/images/favicon.png" />

    <div class="main-panel">
        <div class="content-wrapper pb-0">
            <div class="page-header">
                <h3>Call Information</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Report</li>
                        <li class="breadcrumb-item"><a href="call-list.html">Call List</a></li>
                        <li class="breadcrumb-item active">Information </li>
                    </ol>
                </nav>
            </div>
            <!--/. page header ends-->
            <!-- first row starts here -->
            <div class="row d-flex justify-content-center mb-5">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <h4 class="card-title mt-0">Call ID: CN28956</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-striped" data-role="content" data-plugin="selectable"
                                            data-row-selectable="true">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No</th>
                                                    <th>Label</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Complaint ID</td>
                                                    <td>25847</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Status</td>
                                                    <td>Closed</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Complaint Date</td>
                                                    <td>22-05-2022 <small class="position-static">18:22:58</small></td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>Resolved Date</td>
                                                    <td>23-05-2022 <small class="position-static">10:18:05</small></td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td>OEM Company</td>
                                                    <td>Halma India</td>
                                                </tr>
                                                <tr>
                                                    <td>6</td>
                                                    <td>User Company</td>
                                                    <td>Fire System</td>
                                                </tr>
                                                <tr>
                                                    <td>7</td>
                                                    <td>Project</td>
                                                    <td>Mudra</td>
                                                </tr>
                                                <tr>
                                                    <td>8</td>
                                                    <td>Project State</td>
                                                    <td>Gujarat</td>
                                                </tr>
                                                <tr>
                                                    <td>9</td>
                                                    <td>Project City</td>
                                                    <td>Ahmedabad</td>
                                                </tr>
                                                <tr>
                                                    <td>10</td>
                                                    <td>System</td>
                                                    <td>Mobile</td>
                                                </tr>
                                                <tr>
                                                    <td>11</td>
                                                    <td>Component</td>
                                                    <td>Galaxy S21 Ultra</td>
                                                </tr>
                                                <tr>
                                                    <td>12</td>
                                                    <td>Sub Component</td>
                                                    <td>Galaxy S21 Ultra</td>
                                                </tr>
                                                <tr>
                                                    <td>13</td>
                                                    <td>Reason 1</td>
                                                    <td>Information Only</td>
                                                </tr>
                                                <tr>
                                                    <td>14</td>
                                                    <td>Reason 2</td>
                                                    <td>Data Sheet</td>
                                                </tr>
                                                <tr>
                                                    <td>15</td>
                                                    <td>Reason 3</td>
                                                    <td>Explain over Call</td>
                                                </tr>
                                                <tr>
                                                    <td>16</td>
                                                    <td>Issue</td>
                                                    <td>Device restart automatically</td>
                                                </tr>
                                                <tr>
                                                    <td>17</td>
                                                    <td>Predefined Resolution</td>
                                                    <td>Predefined-1</td>
                                                </tr>
                                                <tr>
                                                    <td>18</td>
                                                    <td>Suggested Resolution</td>
                                                    <td class="ws-break">Contact near by service center, contact details
                                                        provided</td>
                                                </tr>
                                                <tr>
                                                    <td>19</td>
                                                    <td>Support Type</td>
                                                    <td class="ws-break">Service Support</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--responsive table div-->
                                    <!-- image / video row -->
                                    <div class="row">
                                        <!-- level 1 images start -->
                                        <div class="col-md-12 ml-3">
                                            <h4>Level 1 images & videos</h4>
                                            <!-- First row of images -->
                                            <div class="row">
                                                <div class="col-md-2 gallery-box">
                                                    <div data-toggle="modal" data-target="#myModal">
                                                        <img src="../global/assets/images/customer/photo/1.jpg"
                                                            alt="Image 1" data-target="#myCarousel" data-slide-to="0">
                                                    </div>
                                                </div><!-- /.col -->
                                                <div class="col-md-2 gallery-box">
                                                    <div data-toggle="modal" data-target="#myModal">
                                                        <img src="../global/assets/images/customer/photo/4.jpg"
                                                            alt="Image 4" data-target="#myCarousel" data-slide-to="5">
                                                    </div>
                                                </div><!-- /.col -->
                                                <div class="col-md-2 gallery-box">
                                                    <div data-toggle="modal" data-target="#myModal">
                                                        <img src="../global/assets/images/customer/photo/2.jpg"
                                                            alt="Image 2" data-target="#myCarousel" data-slide-to="2">
                                                    </div>
                                                </div><!-- /.col -->

                                                <div class="col-md-2 gallery-box">
                                                    <div data-toggle="modal" data-target="#myModal">
                                                        <img src="../global/assets/images/customer/photo/video.jpg"
                                                            alt="Video 2" data-target="#myCarousel" data-slide-to="3">
                                                    </div>
                                                </div><!-- /.col -->
                                                <div class="col-md-2 gallery-box">
                                                    <div data-toggle="modal" data-target="#myModal">
                                                        <img src="../global/assets/images/customer/photo/video.jpg"
                                                            alt="Video 2" data-target="#myCarousel" data-slide-to="4">
                                                    </div>
                                                </div><!-- /.col -->
                                            </div> <!-- /.row -->
                                            <!-- Lightbox (made with Bootstrap modal and carousel) -->
                                            <!-- Modal -->
                                            <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div> <!-- /.header -->
                                                        <div class="modal-body">
                                                            <!-- Carousel -->
                                                            <div id="myCarousel" class="carousel slide">
                                                                <ol class="carousel-indicators">
                                                                    <li data-target="#myCarousel" data-slide-to="0"
                                                                        class="active"></li>
                                                                    <li data-target="#myCarousel" data-slide-to="1">
                                                                    </li>
                                                                    <li data-target="#myCarousel" data-slide-to="5">
                                                                    </li>
                                                                    <li data-target="#myCarousel" data-slide-to="3">
                                                                    </li>
                                                                    <li data-target="#myCarousel" data-slide-to="2">
                                                                    </li>
                                                                </ol>
                                                                <div class="carousel-inner">
                                                                    <div class="carousel-item active">
                                                                        <img src="../global/assets/images/customer/photo/1.jpg"
                                                                            class="d-block w-100" alt="Image 1">
                                                                    </div>
                                                                    <div class="carousel-item">
                                                                        <div
                                                                            class="embed-responsive embed-responsive-16by9">
                                                                            <img src="../global/assets/images/customer/photo/4.jpg"
                                                                                class="d-block w-100" alt="Image 4">
                                                                        </div>
                                                                    </div>
                                                                    <div class="carousel-item">
                                                                        <img src="../global/assets/images/customer/photo/2.jpg"
                                                                            class="d-block w-100" alt="Image 2">
                                                                    </div>
                                                                    <div class="carousel-item">
                                                                        <div
                                                                            class="embed-responsive embed-responsive-16by9">
                                                                            <iframe class="embed-responsive-item"
                                                                                src="../global/assets/images/customer/video/2185490981.mp4"
                                                                                allowfullscreen></iframe>
                                                                        </div>
                                                                    </div>
                                                                    <div class="carousel-item">
                                                                        <div
                                                                            class="embed-responsive embed-responsive-16by9">
                                                                            <iframe class="embed-responsive-item"
                                                                                src="../global/assets/images/customer/video/2185490981.mp4"
                                                                                allowfullscreen></iframe>
                                                                        </div>
                                                                    </div>
                                                                </div> <!-- /.carousel inner -->
                                                                <a class="carousel-control-prev" href="#myCarousel"
                                                                    role="button" data-slide="prev">
                                                                    <span class="carousel-control-prev-icon"
                                                                        aria-hidden="true"></span>
                                                                    <span class="sr-only">Previous</span>
                                                                </a>
                                                                <a class="carousel-control-next" href="#myCarousel"
                                                                    role="button" data-slide="next">
                                                                    <span class="carousel-control-next-icon"
                                                                        aria-hidden="true"></span>
                                                                    <span class="sr-only">Next</span>
                                                                </a>
                                                            </div> <!-- /.carousel slide -->

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                        </div> <!-- /.footer -->
                                                    </div>
                                                </div>
                                            </div> <!-- /.modal -->
                                        </div> <!-- /.col 12 level 1 images -->
                                        <!-- level 1 recording start -->
                                        <div class="col-md-12 mb-4 ml-3">
                                            <h4>Level 1 Call recording</h4>
                                            <audio controls>
                                                <source src="../global/assets/recordings/Piano.mp3" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                    </div>
                                    <!-- /. image / video row -->
                                </div>
                                <!--/. col 6-->
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped" data-role="content"
                                                    data-plugin="selectable" data-row-selectable="true">
                                                    <thead class="bg-grey-100">
                                                        <tr>
                                                            <th>Sr. No</th>
                                                            <th>Status</th>
                                                            <th>Open at Level</th>
                                                            <th>Date of Updates</th>
                                                            <th>Attend by</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Open</td>
                                                            <td>Level 1</td>
                                                            <td>22-05-2022 <br>
                                                                <small class="position-static">18:22:58</small>
                                                            </td>
                                                            <td>Namita Das</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>Open</td>
                                                            <td>Level 2</td>
                                                            <td>22-05-2022 <br>
                                                                <small class="position-static">23:18:00</small>
                                                            </td>
                                                            <td>Namita Das</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>Closed</td>
                                                            <td>Level 2</td>
                                                            <td>23-05-2022 <br>
                                                                <small class="position-static">10:18:05</small>
                                                            </td>
                                                            <td>Srikant Reddy</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--/.table 1 responsive end-->

                                            <!-- table 2 responsive -->
                                            <div class="table-responsive">
                                                <h4>Level 2 Ticket Details</h4>
                                                <!-- leval 2 ticket details -->
                                                <table class="table table-striped" data-role="content"
                                                    data-plugin="selectable" data-row-selectable="true">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr. No</th>
                                                            <th>Label</th>
                                                            <th>Value</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Executive Name</td>
                                                            <td>Namita Das</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>Status</td>
                                                            <td>Customer Feedback Awaited</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>Predefined Resolution</td>
                                                            <td>Predefined-1</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>Suggested Resolution</td>
                                                            <td class="ws-break">Contact near by service center,
                                                                contact details provided</td>
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>Comments</td>
                                                            <td class="ws-break">Lorem ipsum, or lipsum as it is
                                                                sometimes known, is dummy text used in laying out print,
                                                                graphic or web designs.</td>
                                                        </tr>
                                                    </tbody>
                                                </table>


                                                <!-- level 2 images start -->
                                                <div class="col-12 ml-3">
                                                    <h4>Images & Videos by Namita Das</h4>
                                                    <!-- First row of images -->
                                                    <div class="row">
                                                        <div class="col-md-2 gallery-box">
                                                            <div data-toggle="modal" data-target="#myModal">
                                                                <img src="../global/assets/images/customer/photo/1.jpg"
                                                                    alt="Image 1" data-target="#myCarousel"
                                                                    data-slide-to="0">
                                                            </div>
                                                        </div><!-- /.col -->
                                                        <div class="col-md-2 gallery-box">
                                                            <div data-toggle="modal" data-target="#myModal">
                                                                <img src="../global/assets/images/customer/photo/4.jpg"
                                                                    alt="Image 4" data-target="#myCarousel"
                                                                    data-slide-to="5">
                                                            </div>
                                                        </div><!-- /.col -->

                                                        <div class="col-md-2 gallery-box">
                                                            <div data-toggle="modal" data-target="#myModal">
                                                                <img src="../global/assets/images/customer/photo/video.jpg"
                                                                    alt="Video 2" data-target="#myCarousel"
                                                                    data-slide-to="3">
                                                            </div>
                                                        </div><!-- /.col -->
                                                    </div> <!-- /.row -->
                                                    <!-- Lightbox (made with Bootstrap modal and carousel) -->
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="myModal" tabindex="-1"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div> <!-- /.header -->
                                                                <div class="modal-body">
                                                                    <!-- Carousel -->
                                                                    <div id="myCarousel" class="carousel slide">
                                                                        <ol class="carousel-indicators">
                                                                            <li data-target="#myCarousel"
                                                                                data-slide-to="0" class="active"></li>
                                                                            <li data-target="#myCarousel"
                                                                                data-slide-to="1"></li>
                                                                            <li data-target="#myCarousel"
                                                                                data-slide-to="3"></li>
                                                                        </ol>
                                                                        <div class="carousel-inner">
                                                                            <div class="carousel-item active">
                                                                                <img src="../global/assets/images/customer/photo/1.jpg"
                                                                                    class="d-block w-100" alt="Image 1">
                                                                            </div>
                                                                            <div class="carousel-item">
                                                                                <div
                                                                                    class="embed-responsive embed-responsive-16by9">
                                                                                    <img src="../global/assets/images/customer/photo/4.jpg"
                                                                                        class="d-block w-100"
                                                                                        alt="Image 4">
                                                                                </div>
                                                                            </div>

                                                                            <div class="carousel-item">
                                                                                <div
                                                                                    class="embed-responsive embed-responsive-16by9">
                                                                                    <iframe class="embed-responsive-item"
                                                                                        src="../global/assets/images/customer/video/2185490981.mp4"
                                                                                        allowfullscreen></iframe>
                                                                                </div>
                                                                            </div>
                                                                        </div> <!-- /.carousel inner -->
                                                                        <a class="carousel-control-prev"
                                                                            href="#myCarousel" role="button"
                                                                            data-slide="prev">
                                                                            <span class="carousel-control-prev-icon"
                                                                                aria-hidden="true"></span>
                                                                            <span class="sr-only">Previous</span>
                                                                        </a>
                                                                        <a class="carousel-control-next"
                                                                            href="#myCarousel" role="button"
                                                                            data-slide="next">
                                                                            <span class="carousel-control-next-icon"
                                                                                aria-hidden="true"></span>
                                                                            <span class="sr-only">Next</span>
                                                                        </a>
                                                                    </div> <!-- /.carousel slide -->

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>
                                                                </div> <!-- /.footer -->
                                                            </div>
                                                        </div>
                                                    </div> <!-- /.modal -->
                                                </div> <!-- /.col 12 level 2 images -->

                                                <hr>

                                                <!-- leval 3 ticket details -->
                                                <table class="table table-striped mt-5" data-role="content"
                                                    data-plugin="selectable" data-row-selectable="true">
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Executive Name</td>
                                                            <td>Srikant Reddy</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>Status</td>
                                                            <td>Closed</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>Predefined Resolution</td>
                                                            <td>Predefined-1</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>Suggested Resolution</td>
                                                            <td class="ws-break">Contact near by service center,
                                                                contact details provided</td>
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>Comments</td>
                                                            <td class="ws-break">Lorem ipsum, or lipsum as it is
                                                                sometimes known, is dummy text used in laying out print,
                                                                graphic or web designs.</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--/. responsive 2 table div-->

                                        </div>
                                        <!--/. col 12-->
                                        <hr>
                                        <div class="col-12">
                                            <h3>Customer Information</h3>
                                            <div class="table-responsive">
                                                <table class="table table-striped" data-role="content"
                                                    data-plugin="selectable" data-row-selectable="true">
                                                    <thead class="bg-grey-100">
                                                        <tr>
                                                            <th>Sr. No</th>
                                                            <th>Label</th>
                                                            <th>Value</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Contact</td>
                                                            <td>0000000000</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>Name</td>
                                                            <td>Srinivasa</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>Email</td>
                                                            <td>info@halma-india.com</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>User Company</td>
                                                            <td>Fire System</td>
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>User Company Email</td>
                                                            <td>support@fire-system.com</td>
                                                        </tr>
                                                        <tr>
                                                            <td>6</td>
                                                            <td>Project</td>
                                                            <td>Mundra</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!--/. col 12-->
                                    </div>
                                    <!--/. row-->
                                </div>
                                <!--/. col 6-->
                            </div>

                        </div>
                        <!--card body-->
                    </div>
                    <!--card end-->
                </div>
            </div><!-- end row -->
        </div>
        <!-- content-wrapper ends -->
        <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center d-block d-sm-inline-block">Copyright © 2022 Mas Solutions. All
                    rights reserved.</span>
                <span class="float-none text-black-50 d-block mt-1 mt-sm-0 text-center">Developed by <a href="#">
                        Excellent Computers </a> </span>
            </div>
        </footer>
        <!--/. footer ends-->
    </div>
    <!-- main-panel ends -->
@endsection
<!-- plugins:js -->
<script src="../global/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="../global/assets/js/jquery.cookie.js" type="text/javascript"></script>
<script src="../global/assets/js/settings.js"></script>
<script src="../global/assets/js/custom.js"></script>
<script src="../global/assets/js/off-canvas.js"></script>
<script src="../global/assets/js/hoverable-collapse.js"></script>

<!-- Plugin js for this page -->
<!--lightbox gallery-->
<script src="../global/assets/vendors/wizard/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../global/assets/vendors/wizard/js/jquery-2.2.4.min.js" type="text/javascript"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
    integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
</script>

@extends('chatmodule::chat.layout')

@section('content')
    <!-- char-area -->
    <section class="message-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="chat-area">
                        <!-- chatlist -->
                        <div class="chatlist">
                            <div class="modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="chat-header">
                                        <div class="msg-search">
                                            <input type="text" class="form-control" id="inlineFormInputGroup"
                                                   placeholder="Search" aria-label="search">
                                            <a class="add" href="#"><img class="img-fluid"
                                                                         src="https://img.icons8.com/ios/50/null/plus-2-math.png"
                                                                         alt="add"></a>
                                        </div>

                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="chat-tab" data-bs-toggle="tab"
                                                        data-bs-target="#chat" type="button" role="tab"
                                                        aria-controls="chat" aria-selected="true">Chat
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="modal-body">
                                        <!-- chat-list -->
                                        <div class="chat-lists">
                                            <div class="tab-content" id="tab-content-1">
                                                <div class="tab-pane fade show active" id="chat" role="tabpanel"
                                                     aria-labelledby="chat-tab">
                                                    <!-- chat-list -->
                                                    <div class="chat-list">
                                                        @foreach($users as $user)
                                                            <a href="#" class="d-flex align-items-center chat-user"
                                                               data-user-id="{{ $user->id }}">
                                                                <div class="flex-shrink-0">
                                                                    <img class="img-fluid"
                                                                         src="{{ asset('chatmodule/images/profile-image.jpg') }}"
                                                                         alt="user img">
                                                                    {{--                                                                    <span class="active"></span>--}}
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <h3>{{ $user->name }}</h3>
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                    <!-- chat-list -->
                                                </div>
                                            </div>

                                        </div>
                                        <!-- chat-list -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- chatlist -->


                        <!-- chatbox -->
                        <div class="chatbox">
                            <div id="chat-box-container" class="modal-dialog-scrollable d-none">
                                <div class="modal-content">
                                    <div class="msg-head">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="d-flex align-items-center">
                                                    <span class="chat-icon"><img class="img-fluid"
                                                                                 src="https://mehedihtml.com/chatbox/assets/img/arroleftt.svg"
                                                                                 alt="image title"></span>
                                                    <div class="flex-shrink-0">
                                                        <img class="img-fluid chat-user-image"
                                                             id="chat-user-image"
                                                             src="{{ asset('chatmodule/images/profile-image.jpg') }}"
                                                             alt="user img">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h3 id="chat-user-name"></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--<div class="col-4">
                                                <ul class="moreoption">
                                                    <li class="navbar nav-item dropdown">
                                                        <a class="nav-link dropdown-toggle" href="#" role="button"
                                                           data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                    class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                                            <li><a class="dropdown-item" href="#">Another action</a>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item" href="#">Something else
                                                                    here</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>--}}
                                        </div>
                                    </div>


                                    <div class="modal-body">
                                        <div class="msg-body">
                                            <ul id="chat-messages">
                                                {{--                                                <li class="sender">--}}
                                                {{--                                                    <p> Hey, Are you there? </p>--}}
                                                {{--                                                    <span class="time">10:06 am</span>--}}
                                                {{--                                                </li>--}}
                                                {{--                                                <li class="sender">--}}
                                                {{--                                                    <p> Hey, Are you there? </p>--}}
                                                {{--                                                    <span class="time">10:16 am</span>--}}
                                                {{--                                                </li>--}}
                                                {{--                                                <li class="repaly">--}}
                                                {{--                                                    <p>yes!</p>--}}
                                                {{--                                                    <span class="time">10:20 am</span>--}}
                                                {{--                                                </li>--}}
                                                {{--                                                <li class="sender">--}}
                                                {{--                                                    <p> Hey, Are you there? </p>--}}
                                                {{--                                                    <span class="time">10:26 am</span>--}}
                                                {{--                                                </li>--}}
                                                {{--                                                <li class="sender">--}}
                                                {{--                                                    <p> Hey, Are you there? </p>--}}
                                                {{--                                                    <span class="time">10:32 am</span>--}}
                                                {{--                                                </li>--}}
                                                {{--                                                <li class="repaly">--}}
                                                {{--                                                    <p>How are you?</p>--}}
                                                {{--                                                    <span class="time">10:35 am</span>--}}
                                                {{--                                                </li>--}}
                                                {{--                                                <li>--}}
                                                {{--                                                    <div class="divider">--}}
                                                {{--                                                        <h6>Today</h6>--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                </li>--}}

                                                {{--                                                <li class="repaly">--}}
                                                {{--                                                    <p> yes, tell me</p>--}}
                                                {{--                                                    <span class="time">10:36 am</span>--}}
                                                {{--                                                </li>--}}
                                                {{--                                                <li class="repaly">--}}
                                                {{--                                                    <p>yes... on it</p>--}}
                                                {{--                                                    <span class="time">junt now</span>--}}
                                                {{--                                                </li>--}}

                                            </ul>
                                        </div>
                                    </div>


                                    <div class="send-box">
                                        <form id="chat-message-form">
                                            <input type="hidden" name="channel_id">
                                            <input type="text" name="message" class="form-control" aria-label="message…"
                                                   placeholder="Write message…">

                                            <button type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                Send
                                            </button>
                                        </form>

                                        <div class="send-btns">
                                            <div class="attach">
                                                <div class="button-wrapper">
                                                    <span class="label">
                                                        <img class="img-fluid"
                                                             src="https://img.icons8.com/ios/50/null/upload--v1.png"
                                                             alt="image title"> attached file
                                                    </span><input type="file" name="upload" id="upload"
                                                                  class="upload-box" placeholder="Upload File"
                                                                  aria-label="Upload File">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- chatbox -->


                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@push('page-scripts')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
            integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>

    <script type="module">
        import Echo from 'https://cdn.jsdelivr.net/npm/laravel-echo@^1.11/dist/echo.min.js'

        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            authEndpoint: "{{url('/broadcasting/auth')}}",
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            wsHost: window.location.hostname,
            wsPort: 3001,
            forceTLS: false,
            disableStats: true,
        });
    </script>

    <script>
        $(function () {
            let is_chat_next_url = null
            let active_channel_id = null
            let active_channel = null
            let messages = []
            const auth_id = '{{ \Auth::id() }}'


            const $post = (url, params) => {
                const _token = '{{ csrf_token() }}'
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '{{url('/') . '/' . config('chatmodule.prefix')}}' + url,
                        method: "post",
                        data: {...params, _token},
                        success: function (res) {
                            resolve(res)
                        },
                        error: function (error) {
                            reject(error)
                        }
                    })
                })
            }

            const $get = (url, params) => {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '{{url('/') . '/' . config('chatmodule.prefix')}}' + url,
                        data: {...params},
                        success: function (res) {
                            resolve(res)
                        },
                        error: function (error) {
                            reject(error)
                        }
                    })
                })
            }

            const messageRender = () => {
                $('#chat-messages').html("")
                let _html = ''
                for (let message of messages) {
                    const time = moment(message.created_at).fromNow()
                    if (auth_id == message.sender_id) {
                        _html += `<li class="repaly">
                                    <p>${message.content}</p>
                                    <span class="time">${time}</span>
                                </li>`
                    } else {
                        _html += `<li class="sender">
                                    <p>${message.content}</p>
                                    <span class="time">${time}</span>
                                </li>`
                    }
                }
                $('#chat-messages').html(_html)
                $('#chat-box-container .modal-body').scrollTop($('#chat-box-container .modal-body .msg-body')[0]?.scrollHeight ?? 0)
            }

            const connectChannel = (channel_id) => {
                if (active_channel_id) {
                    window.Echo.leave(`Chat.Channel.${active_channel_id}`)
                }
                active_channel_id = channel_id
                active_channel = window.Echo.private(`Chat.Channel.${channel_id}`)
                    .listen('.chat.message', (e) => {
                        console.log("socket data", e);
                    });
            }

            $(document).on('submit', '#chat-message-form', function (e) {
                e.preventDefault()

                const message = $(this).find('input[name="message"]').val()
                if (message.trim().length < 1) return

                const channel_id = $(this).find('input[name="channel_id"]').val()

                $(this).find('input[name="message"]').val('')
                messages.push({
                    created_at: moment().format(),
                    content: message,
                    sender_id: auth_id
                })
                messageRender()
                // $('#chat-messages').append(`<li class="repaly">
                //                                 <p>${message}</p>
                //                                 <span class="time">junt now</span>
                //                             </li>`)
                // $('#chat-box-container .modal-body').scrollTop($('#chat-box-container .modal-body .msg-body')[0]?.scrollHeight ?? 0)

                $post('/chat/messages', {message, channel_id}).then(res => {
                    console.log("res", res)
                })
            })

            $(document).on('click', '.chat-user', function (e) {
                e.preventDefault()
                const user_id = $(this).data('user-id')
                $post('/channel/create', {user_id}).then(res => {
                    $('#chat-box-container').removeClass('d-none')
                    $('#chat-user-image').attr('src', res.data.cover_data.profile_img)
                    $('#chat-user-name').text(res.data.cover_data.name)
                    $('#chat-message-form').find('input[name="channel_id"]').val(res.data.id)
                    $('#chat-messages').html("")

                    $get('/chat/messages', {channel_id: res.data.id, page: 1}).then(res => {
                        is_chat_next_url = res.next_page_url
                        messages = res.data.reverse()
                        messageRender()
                    })
                    connectChannel(res.data.id)
                    // console.log("res", res.data)
                })
            })


        })
    </script>
@endpush
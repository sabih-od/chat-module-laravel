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
                                        </div>
                                    </div>


                                    <div class="modal-body">
                                        <div class="msg-body">
                                            <ul id="chat-messages">
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
                                                    </span><input type="file" multiple name="upload" id="upload"
                                                                  class="upload-box" placeholder="Upload File"
                                                                  aria-label="Upload File">
                                                </div>
                                            </div>
                                            <div id="selected-files" class="d-flex flex-wrap"></div>
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
            let load_more = false
            let messages = []
            const auth_id = '{{ \Auth::id() }}'
            let attach_files = []

            const uuidv4 = () => {
                return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
                    (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
                );
            }

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

            const $get = (url, params, full_url = false) => {
                return new Promise((resolve, reject) => {
                    let _url = full_url ? url : '{{url('/') . '/' . config('chatmodule.prefix')}}' + url

                    $.ajax({
                        url: _url,
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

            const messageRender = (is_scroll_top = true) => {
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
                if (is_scroll_top)
                    $('#chat-box-container .modal-body').scrollTop($('#chat-box-container .modal-body .msg-body')[0]?.scrollHeight ?? 0)
            }

            let attachFilesRender = () => {
                let _html = ''
                for (const key in attach_files) {
                    _html += `<span data-ind='${key}' class='badge text-bg-light me-1 mb-1'><i class="fa fa-times me-1 remove-file"></i> ${attach_files[key].name}</span>`
                }
                $('#selected-files').html(_html)
            }

            const connectChannel = (channel_id) => {
                if (active_channel_id) {
                    window.Echo.leave(`Chat.Channel.${active_channel_id}`)
                }
                active_channel_id = channel_id
                active_channel = window.Echo.private(`Chat.Channel.${channel_id}`)
                    .listen('.chat.message', (e) => {
                        console.log("socket data", e);
                        console.log("messages", messages)
                        const found = messages.filter((item) => {
                            return item.id == e.data.id
                        })
                        if (found.length < 1) {
                            messages.push(e.data)
                            messageRender()
                        }
                    });
            }

            const loadMoreMessages = () => {
                if (load_more || !is_chat_next_url) return;
                load_more = true

                $get(is_chat_next_url, {channel_id: active_channel_id}, true).then(res => {
                    is_chat_next_url = res.next_page_url
                    messages = [...res.data.reverse(), ...messages]
                    messageRender(false)
                }).finally(() => {
                    load_more = false
                })
            }

            $(document).on('click', '.remove-file', function (e) {
                e.preventDefault()
                const index = $(this).parent().data('ind')
                if (attach_files.hasOwnProperty(index)) {
                    attach_files.splice(index, 1)
                }
                attachFilesRender()
            })

            $('#chat-box-container .modal-body').on('scroll', function (e) {
                let div = $(this).get(0);
                if (div.scrollTop < 50) {
                    loadMoreMessages()
                }
            })

            $('#upload').on('change', function (e) {
                console.log("file", e.target.files)
                const files = e.target.files
                for (const key in files) {
                    if (files[key]?.type) {
                        attach_files.push(files[key])
                    }
                }
                $(this).val(null)
                attachFilesRender()
            })

            $(document).on('submit', '#chat-message-form', function (e) {
                e.preventDefault()

                const message = $(this).find('input[name="message"]').val()
                if (message.trim().length < 1 && attach_files.length < 1) return

                return console.log("attach_files", attach_files)


                const channel_id = $(this).find('input[name="channel_id"]').val()

                $(this).find('input[name="message"]').val('')
                const id = uuidv4()
                messages.push({
                    id,
                    created_at: moment().format(),
                    content: message,
                    sender_id: auth_id
                })
                messageRender()

                $post('/chat/messages', {message, channel_id}).then(res => {
                    messages = messages.map((item) => {
                        if (item.id == id) {
                            item.id = res.data.id
                        }
                        return item
                    })
                    messageRender()
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
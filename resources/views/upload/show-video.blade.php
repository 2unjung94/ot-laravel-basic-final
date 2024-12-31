<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $video->title }} 재생</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://vjs.zencdn.net/7.11.4/video-js.css" rel="stylesheet" />

    <!-- Video.js CDN -->
    <script src="https://vjs.zencdn.net/7.11.4/video.min.js"></script>
    <!-- videojs-dynamic-watermark 플러그인 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/videojs-dynamic-watermark/dist/videojs-dynamic-watermark.min.js"></script>
</head>

<body class="bg-black flex items-center justify-center min-h-screen bg-white">

    <!-- 동영상 플레이어 섹션 -->
    <div class="w-4/5 flex items-center justify-center">

    <div class="w-9/12 mr-5 ">
            <video id="my-video" class="video-js vjs-big-play-centered mt-10 ml-5"
                data-setup='{"controls": true, "fluid": true, "autoplay": false, "muted": true, "playbackRates": [0.5, 1, 1.5, 2]}'
                preload="auto" controlsList="nodownload">
                <!-- 동영상 소스 -->
                <source src= "{{ asset($fullpath) }}" type="video/mp4">
                브라우저가 동영상 태그를 지원하지 않습니다.
            </video>
        </div>
    <!-- 목록 섹션 (오른쪽에 위치) -->
    <div class="w-1/5 p-4 mt-10 ml-5 border-l border-black">
            <h2 class=" border rounded p-2 text-lg font-bold mb-4 text-white  bg-blue-700 text-center">Class Session !</h2>
            @foreach ($videos as $video)
                <ul class="space-y-2">
                    <li class="border rounded bg-gray-100 hover:bg-blue-100 p-2  text-center font-bold mt-5">
                        <button class="video-button" data-video-src="{{ asset($video->file_path) }}">
                            <p>
                                <i class="fa-regular fa-circle-check" style="color: red"></i> Laravel 입문
                            </p>
                            {{ $video->title }}
                        </button>
                    </li>
                    <!-- 필요에 따라 추가적인 목록 아이템을 추가할 수 있습니다 -->
                </ul>
            @endforeach
        </div>
    </div>
    <script>
        // Video.js로 동영상 플레이어 생성
        var player = videojs("my-video");
        var id = "{{ $video->user->username }}";

        // 동적 워터마크 설정
        player.dynamicWatermark({
            elementId: "unique_id", // 워터마크의 고유 ID
            watermarkText: id, // 워터마크로 사용할 HTML 또는 텍스트
            changeDuration: 1000, // 워터마크 변경 간격 (밀리초)
            cssText: "display: inline-block; color: grey; background-color: transparent; font-size: 1rem; z-index: 9999; position: absolute; @media only screen and (max-width: 992px){font-size: 0.8rem;}", // 워터마크 스타일 설정
        });

        // 버튼 이벤트
        document.querySelectorAll(".video-button").forEach(function (button) {
            button.addEventListener("click", function () {
                var videoSrc = button.getAttribute("data-video-src");
                player.src({
                    src: videoSrc,
                    type: "video/mp4"
                });
                player.play();
            });
        });
    </script>
</body>

</html>


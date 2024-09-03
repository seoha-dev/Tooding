<section class="section-index-top m-auto">
    <div class="section-inner text-center d-flex justify-content-center h-100 align-items-center m-auto">
        <div class="video-wrap w-100 h-100">
            <video autoplay loop muted preload="auto">
                <source type="video/mp4" src="/public/videos/18069232-hd_1280_720_24fps.mp4"
            </video>
        </div>
        <div class="video-cover-wrap">
        </div>
        <a class="btn btn-primary btn-lg start-btn" href="/todo">게스트로 체험하기</a>
    </div>
</section>

<style>

    .section-index-top {
        width: 100%;
        height: 40em;
    }

    .section-index-top .section-inner {
        width: 100%;
        /*max-width: 1240px;*/
        position: relative;
    }

    .section-inner .video-wrap {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 0;
    }

    .section-inner .video-wrap video {
        width: 100%;
        height: 100%;
        overflow: hidden;
        object-fit: cover;
    }

    .video-cover-wrap {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 9;
        background-color: rgba(0, 0, 0, 0.3);
        width: 100%;
        height: 100%;
    }

    .start-btn {
        z-index: 99;
    }

</style>
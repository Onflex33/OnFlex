<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-700">
    <!--div>
        { { $logo }}
    </div-->

    <div class = "sm:w-1/2 md:w-1/3 text-green-400 font-bold text-center lg:text-6xl md:text-5xl sm:text-4xl">
        <img src="{{asset('img/onflex-logo.png')}}" alt="OnFlex">
    </div>

    <div class="sm:w-5/6 md:w-1/2 lg:w-1/3 mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>

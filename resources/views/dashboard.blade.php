<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    @if(!isset($softbrick))
    <div class="text-center pt-12 lg:px-4">
        <a href="{{ route('settings') }}">
            <div class="p-2 bg-green-800 items-center text-green-100 leading-none lg:rounded-full flex lg:inline-flex" role="alert">
                <span class="flex rounded-full bg-green-500 uppercase px-2 py-1 text-xs font-bold mr-3">Registratie</span>
                <span class="font-semibold mr-2 text-left flex-auto">Klik hier om je Softbrick gegevens te registreren</span>
                <svg class="fill-current opacity-75 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.95 10.707l.707-.707L8 4.343 6.586 5.757 10.828 10l-4.242 4.243L8 15.657l4.95-4.95z"/></svg>
            </div>
        </a>
    </div>
    @endisset

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col sm:justify-center items-center">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <a href="#" class="bg-indigo-500 text-white font-bold py-2 px-4 rounded inline-flex items-center opacity-50 cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 30 30" style=" fill:#fff;"><path d="M 15.003906 3 C 8.3749062 3 3 8.373 3 15 C 3 21.627 8.3749062 27 15.003906 27 C 25.013906 27 27.269078 17.707 26.330078 13 L 25 13 L 22.732422 13 L 15 13 L 15 17 L 22.738281 17 C 21.848702 20.448251 18.725955 23 15 23 C 10.582 23 7 19.418 7 15 C 7 10.582 10.582 7 15 7 C 17.009 7 18.839141 7.74575 20.244141 8.96875 L 23.085938 6.1289062 C 20.951937 4.1849063 18.116906 3 15.003906 3 z"></path></svg>
                        Abonneer op agenda (Google)
                    </a>
                    <a href="#" class="bg-indigo-500 text-white font-bold py-2 px-4 rounded inline-flex items-center opacity-50 cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 50 50" style=" fill:#fff;"><path d="M 8.421875 4 C 8.421875 4 9.96875 4.332031 11.183594 4.332031 C 12.398438 4.332031 13.945313 4 13.945313 4 L 25.023438 22.957031 L 36.164063 4 C 36.164063 4 37.378906 4.441406 38.816406 4.441406 C 40.363281 4.441406 41.578125 4 41.578125 4 L 27.234375 28.484375 L 28 46 C 28 46 26.105469 45.558594 25 45.558594 C 23.894531 45.558594 21.980469 46 21.980469 46 L 22.8125 28.484375 Z"></path></svg>
                        Abonneer op agenda (Yahoo)
                    </a>
                    <a href="#" class="bg-indigo-500 text-white font-bold py-2 px-4 rounded inline-flex items-center opacity-50 cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 30 30" style=" fill:#fff;"><path d="M 15.003906 3 C 8.3749062 3 3 8.373 3 15 C 3 21.627 8.3749062 27 15.003906 27 C 25.013906 27 27.269078 17.707 26.330078 13 L 25 13 L 22.732422 13 L 15 13 L 15 17 L 22.738281 17 C 21.848702 20.448251 18.725955 23 15 23 C 10.582 23 7 19.418 7 15 C 7 10.582 10.582 7 15 7 C 17.009 7 18.839141 7.74575 20.244141 8.96875 L 23.085938 6.1289062 C 20.951937 4.1849063 18.116906 3 15.003906 3 z"></path></svg>
                        Abonneer op agenda (Outlook web)
                    </a>
                    <a href="#" class="bg-indigo-500 text-white font-bold py-2 px-4 rounded inline-flex items-center opacity-50 cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" fill="#fff"><g transform="matrix(.036771 0 0 .036771 3 0)"><defs><path id="A" d="M0 0h708.7v870.3H0z"/><path id="B" d="M591.8 462.4c-1-110.1 90-163 94.1-165.6-51.2-74.9-130.8-85.1-159.2-86.3-67.7-6.9-132.2 39.9-166.6 39.9s-87.4-38.9-143.6-37.8c-73.9 1.1-142 42.9-180.1 109.1-76.7 133-19.6 330.3 55.2 438.4 36.6 52.8 80.1 112.3 137.4 110.1 55.2-2.2 76-35.7 142.6-35.7s85.4 35.7 143.6 34.6c59.3-1.2 96.9-54 133.2-107 41.9-61.3 59.2-120.7 60.2-123.8-1.3-.5-115.6-44.3-116.8-175.9"/></defs><clipPath id="C"><use xlink:href="#A"/></clipPath><g clip-path="url(#C)"><use xlink:href="#B"/></g><path d="M482.3 139c30.4-36.8 50.9-88 45.3-139-43.8 1.8-96.8 29.2-128.2 66-28.1 32.5-52.8 84.6-46.1 134.6 48.7 3.8 98.6-24.9 129-61.6"/></g><g clip-path="url(#C)" transform="matrix(.098524 0 0 .098524 89.579758 -18.770519)"><use xlink:href="#B"/></g><path d="M137.098-5.076c2.995-3.626 5.015-8.67 4.463-13.695-4.315.177-9.537 2.877-12.63 6.503-2.77 3.202-5.202 8.335-4.542 13.26 4.798.374 9.714-2.453 12.71-6.07"/><path d="M185.7 37.457l-4.966 15.045h-6.384l16.247-47.824h7.448l16.316 47.824h-6.6l-5.113-15.045zm15.685-4.828l-4.68-13.764c-1.064-3.123-1.773-5.96-2.483-8.73h-.138l-2.414 8.66L187 32.63z" enable-background="new "/><path d="M219.208 29.368c0-4.394-.138-7.95-.286-11.212h5.606l.286 5.892h.138c2.552-4.187 6.6-6.67 12.207-6.67 8.306 0 14.542 7.025 14.542 17.458 0 12.345-7.517 18.444-15.606 18.444-4.542 0-8.512-2-10.572-5.4h-.138v18.66h-6.177zm6.168 9.153c0 .926.138 1.773.286 2.552 1.133 4.325 4.897 7.3 9.37 7.3 6.6 0 10.434-5.4 10.434-13.27 0-6.887-3.616-12.77-10.217-12.77-4.256 0-8.227 3.054-9.44 7.734-.217.778-.424 1.704-.424 2.552v5.892zm32.778-9.153c0-4.394-.138-7.95-.286-11.212h5.606l.286 5.892h.138c2.552-4.187 6.6-6.67 12.207-6.67 8.306 0 14.542 7.025 14.542 17.458 0 12.345-7.517 18.444-15.606 18.444-4.542 0-8.512-2-10.572-5.4h-.138v18.66h-6.177zm6.177 9.153c0 .926.138 1.773.286 2.552 1.133 4.325 4.897 7.3 9.37 7.3 6.6 0 10.434-5.4 10.434-13.27 0-6.887-3.616-12.77-10.217-12.77-4.256 0-8.227 3.054-9.44 7.734-.217.778-.424 1.704-.424 2.552v5.892zM297.1 2.126h6.246V52.5H297.1zm18.8 34.336c.138 8.444 5.537 11.92 11.774 11.92 4.473 0 7.163-.778 9.508-1.773l1.064 4.473c-2.197.995-5.96 2.128-11.42 2.128-10.572 0-16.887-6.956-16.887-17.3s6.1-18.523 16.1-18.523c11.212 0 14.187 9.862 14.187 16.178 0 1.28-.138 2.266-.217 2.906zm18.306-4.473c.07-3.97-1.635-10.148-8.66-10.148-6.315 0-9.084 5.823-9.577 10.148z" enable-background="new "/></svg>
                        Abonneer op agenda (ics)
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

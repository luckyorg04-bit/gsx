
    <div class="container-wrapper  homebanner_sec  parallax relative">
    <div class="overlay flex">
    <div class="container mx-auto flex flex-col items-center justify-center">
        <div class="text-center w-4/5 mx-auto">
            <h1 class="text-6xl font-bold text-white">School Management to manage the entire campus Operations and Administration</h1>
            <!-- <h1 class="text-6xl font-bold text-white">Improve Your Skill & Learn Excilence In Teaching</h1> -->
        </div>
         <div class="py-8 flex mx-auto justify-start">
                    @auth
                    @if(in_array(Auth::user()->usergroup_id, [3, 5, 7], true))
                        <a href="{{ url('/admin/dashboard') }}" class="button p-4 bg-white border-l-4 border-blue-500 mr-2 font-bold">Go To Dashboard</a>
                    @elseif(Auth::user()->usergroup_id == 6)
                        <a href="{{ url('/student/dashboard') }}" class="button p-4 bg-white border-l-4 border-blue-500 mr-2 font-bold">Go To Dashboard</a>
                    @else
                        <a href="{{ url('/register') }}" class="button px-8 py-4 bg-white border-l-4 border-blue-500 mr-2 font-bold">Register as School</a>
                    @endif
                @else
                    <a href="{{ url('/register') }}" class="button px-8 py-4 bg-white border-l-4 border-blue-500 mr-2 font-bold">Register as School</a>
                @endauth
                </div>
    </div>
</div>
</div>
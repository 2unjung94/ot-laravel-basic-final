<x-guest-layout>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Username -->
            <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="name" placeholder="영문/숫자/-만 가능" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
          <x-input-label for="email" :value="__('Email')" />
          <div class="flex justify-between">
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="name" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div class="ml-4">
              <button type="button" class="btn bg-indigo-500 hover:bg-indigo-600 text-white whitespace-nowrap py-2 px-4" onclick="sendEmailVerification()">{{ __('이메일인증') }}</button>
            </div>
          </div>
        </div>
        
        <div class="mt-4">
          <x-input-label for="verify" :value="__('이메일 인증번호')" />
          <div class="flex justify-between">
              <x-text-input type="text" class="block mt-1 w-full" id="verify" name="verify" placeholder="이메일 인증번호"/>
              <x-input-error :messages="$errors->get('verify')" class="mt-2" />
            <div class="ml-4">
              <button type="button" class="btn bg-indigo-500 hover:bg-indigo-600 text-white whitespace-nowrap py-2 px-4" onclick="verification()">{{  __('인증하기') }}</button>
            </div>
          </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
  // 이메일 인증번호 전송
  function sendEmailVerification(){
    const email = document.getElementById('email').value;
    if(!email){
      alert('이메일을 입력해주세요.');
      return;
    }
    console.log("이메일 전송 시작:", email); // 이메일 전송 시작 로그

    $.ajax({
      url: "{{ route('users.email') }}",
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      data: JSON.stringify({
        email: email
      }),
      dataType: 'json',
      success: function(data){
        console.log("응답 데이터:", data); // 성공 시 응답 데이터 로그
        if(data.success){
          alert('이메일이 전송되었습니다.');
        } else{
          alert('이메일 전송에 실패했습니다.');
        }
      },
      error: function(xhr, status, error){
        console.error("AJAX 요청 실패:", error); // 요청 실패 시 에러 로그
        console.error("상태 코드:", status);
        console.error("응답 내용:", xhr.responseText);
        alert('이메일 전송에 실패했습니다.');
      }
    });
  }

  function verification(){
    const code = document.getElementById('verify').value;
    if(!code){
      alert('인증번호를 입력해주세요.');
      return;
    }
    console.log("인증번호 전송 시작:", code); // 인증번호 전송 시작 로그

    $.ajax({
      url: "{{ route('users.verify') }}",
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      data: JSON.stringify({
        code: code
      }),
      dataType: 'json',
      success: function(data){
        console.log("응답 데이터:", data); // 성공 시 응답 데이터 로그
        if(data.success){
          alert('인증에 성공했습니다.');
        } else{
          alert('인증에 실패했습니다.');
        }
      },
      error: function(xhr, status, error){
        console.error("AJAX 요청 실패:", error); // 요청 실패 시 에러 로그
        console.error("상태 코드:", status);
        console.error("응답 내용:", xhr.responseText);
        alert('인증에 실패했습니다.');
      }
    });
  }
</script>
</x-guest-layout>

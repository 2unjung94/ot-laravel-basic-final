<!-- resources/views/components/action-dropdown.blade.php -->

@props(['model', 'destroyRoute', 'editRoute'])
<div class="top-0 right-0">
  <div class="hidden sm:flex sm:items-center sm:ms-6">
      <x-dropdown align="right" width="48">
          <x-slot name="trigger">
              <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                  <div>더보기</div>
                  <div class="ms-1">
                      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                      </svg>
                  </div>
              </button>
          </x-slot>
  
          <x-slot name="content">
              <!-- 삭제 폼 -->
              <form action="{{ route($destroyRoute, [strtolower(class_basename($model)) => $model->id]) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <x-dropdown-link :href="route($destroyRoute, [strtolower(class_basename($model)) => $model->id])"
                                   onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-500">
                      {{ __('삭제') }}
                  </x-dropdown-link>
              </form>
  
              <!-- 수정 링크 -->
               @if(strtolower(class_basename($model)) === 'comment')
               <x-dropdown-link href="#" onclick="toggleEditForm(event, 'edit-form-{{ $model->id }}')">
                  {{ __('댓글수정') }}
              </x-dropdown-link>
              @elseif(strtolower(class_basename($model)) === 'article')
              <x-dropdown-link 
                  href="{{route($editRoute, [strtolower(class_basename($model)) => $model->id])}}" >
                  {{ __('글수정') }}
              </x-dropdown-link> 
              @endif
          </x-slot>
      </x-dropdown>
  </div>
</div>

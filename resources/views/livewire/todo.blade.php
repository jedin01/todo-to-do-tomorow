<?php

use function Livewire\Volt\{state, with};
use App\Models\Task;

state(['content', 'tasks' => Task::orderBy('checked')->get(), 'filtro' => 'todos']);

$filtrar = function () {
    if ($this->filtro == 'todos') {
        $this->tasks = Task::all();
    } elseif ($this->filtro == 'feitos') {
        $this->tasks = Task::where('checked', true)->get();
    } elseif ($this->filtro == 'pendentes') {
        $this->tasks = Task::where('checked', false)->get();
    }
};

$adicionar = function () {
    Task::create([
        'desc' => $this->content,
    ]);
    $this->tasks = Task::orderBy('checked')->get();
    $this->content = '';
};

$toggleCheck = function ($id) {
    Task::where('id', $id)->update([
        'checked' => \App\Models\Task::find($id)->checked == 1 ? 0 : 1,
    ]);
    if ($this->filtro == 'todos') {
        $this->tasks = Task::orderBy('checked')->get();
    } elseif ($this->filtro == 'feitos') {
        $this->tasks = Task::orderBy('checked')->get();
    } elseif ($this->filtro == 'pendentes') {
        $this->tasks = Task::orderBy('checked')->get();
    }
};

?>

<div
    class='transition-[0.5s] w-[400px] bg-violet-950 text-white p-[10px] flex flex-col items-center justify-center rounded-[5px] pb-4 shadow-md'>
    <h1 class="font-bold text-[25px] text-center mb-4">todo</h1>
    <form wire:submit='adicionar' class="mb-[15px] flex justify-between w-[100%] px-[10px]">
        <input type="text" wire:model.live='content' class="text-black h-[35px] focus:border-violet-800 rounded-l-md"
            placeholder="Escreva uma tarefa...">
        <input type="submit" value="Adicionar" class="bg-violet-800  rounded-r-md font-bold w-[150px] h-[35px]">
    </form>
    <div>

    </div>
    <div class="flex justify-between w-[100%] p-[20px]">
        <label for="todos" class="flex gap-2 items-center justify-center"><span>Todos</span><input type="radio"
                id="todos" value="todos" wire:model.live='filtro' wire:click='filtrar'></label>
        <label for="feitos" class="flex gap-2 items-center justify-center"><span>Feitos</span><input type="radio"
                id="feitos" value="feitos" wire:model.live='filtro' wire:click='filtrar'></label>
        <label for="pendentes" class="flex gap-2 items-center justify-center"><span>Pendentes</span><input
                type="radio" id="pendentes" value="pendentes" wire:model.live='filtro' wire:click='filtrar'></label>
    </div>

    <div class="w-[100%] px-[10px] text-white flex flex-col gap-2">

        @foreach ($tasks as $task)
            <div
                class="flex items-center w-[100%] justify-between bg-violet-900 p-2 rounded-[5px] borde border-t- border-t-violet-400">

                <div class="flex items-center gap-2">
                    <label for="check{{ $task->id }}"
                        class="transition-[0.5s] active:scale-90 w-[30px] h-[30px] border-[3px] rounded-[50px] border-violet-600 @if ($task->checked) bg-violet-600 @else bg-violet-900 @endif flex items-center justify-center">
                        <span
                            class="text-[20px] font-bold  @if ($task->checked) text-white
                            @else
                            text-violet-500 @endif ">&checkmark;</span>

                        <input class="hidden" id="check{{ $task->id }}" type="checkbox"
                            @if ($task->checked) checked @endif
                            wire:click='toggleCheck({{ $task->id }})'>
                    </label>
                    <span class="text-violet-300 font-bold">0{{ $task->id }}</span>
                    <span class="@if ($task->checked) line-through text-violet-300 @endif">{{ $task->desc }} </span>
                </div>

                <span class="text-[12px] text-violet-300">{{ $task->updated_at }}</span>
            </div>
        @endforeach
    </div>

</div>

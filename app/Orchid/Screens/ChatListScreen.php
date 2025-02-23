<?php

namespace App\Orchid\Screens;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ChatListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'chats' => Chat::latest()->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Чаты';
    }

    public function description(): ?string
    {
        return 'управление чатами';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Создать чат')
                ->modal('chatModal')
                ->method('create')
                ->icon('plus')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('chats', [
                TD::make('slug'),
                TD::make('Actions')
                    ->alignRight()
                    ->render(function (Chat $chat) {
                        return Button::make('Удалить чат')
                            ->confirm('Чат будет безвозвратно удален')
                            ->method('delete', ['chat' => $chat->id]);
                    }),
            ]),
            Layout::modal('chatModal', Layout::rows([
                Input::make('chat.slug')
                ->title('Slug')
                ->placeholder('Введите системное имя чата на английском языке')
                ->help('Системное имя чата или Slug нужен для идентификации чата в человеко-понятном виде'),
            ]))
                ->title('Создать чат')
                ->applyButton('Создать'),
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function create(Request $request)
    {
        // Validate form data, save chat to database, etc.
        $request->validate([
            'chat.slug' => 'required|max:255',
        ]);

        $chat = new Chat();
        $chat->slug = $request->input('chat.slug');
        $chat->user1 = Auth::id();
        $chat->user2 = Auth::id();
        $chat->save();
    }
}

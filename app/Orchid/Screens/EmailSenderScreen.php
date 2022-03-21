<?php

namespace App\Orchid\Screens;
use App\Models\User;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Orchid\Support\Facades\Alert;

class EmailSenderScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
            return [
        'subject' => date('F').' Campaign News',
    ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'name():Email sender';
    }

    public function description(): ?string {
        return "description():Tool that sends ad-hoc email messages.";
    }
    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Send Message')
                ->icon('paper-plane')
                ->method('sendMessage'),
                   
            Button::make('Send Message')
                ->confirm()
                ->set('title', 'commandBar():title') // ovako dodajemo old-style html atribute
                ->icon('paper-plane')
                ->method('sendMessage'),     
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
         return [
        Layout::rows([
            Input::make('subject')
                ->title('Subject')
                ->required()
                ->placeholder('Message subject line')  // ovo polje je zamijenjeno s metodom query()
                //value="March Campaign News"
                ->help('Enter the subject line for your message'),

           // Relation::make('users')   // radi i ovako, cemu tockica????
            Relation::make('users.')
                ->title('Recipients')
                ->multiple()
                ->required()
                ->placeholder('Email addresses')
                ->help('Enter the users that you would like to send this message to.')
                ->fromModel(User::class,'name','email'),
               // ->fromModel(User::class,'password','email'),

            Quill::make('content')
                ->title('Content')
                ->required()
                ->placeholder('Insert text here ...')
                ->help('Add the content for the message that you would like to send.')
                ,
            Button::make('Send Message from below')
               // ->confirm()
                ->set('title', 'commandBar():title') // ovako dodajemo old-style html atribute
                ->icon('paper-plane')
                ->method('messageFromBellow'),   

        ]) 
    ];
    }
    
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'subject' => 'required|min:6|max:50',
            'users'   => 'required',
            'content' => 'required|min:10'
        ]);

        Mail::raw($request->get('content'), function (Message $message) use ($request) {
            $message->from('sample@email.com');
            $message->subject($request->get('subject'));

            foreach ($request->get('users') as $email) {
                $message->to($email);
            }
        });


        Alert::info('Your email message has been sent successfully.');
    }
    
    
        public function messageFromBellow()
    {
       
        Alert::info('Hello from below the form!');
    }
}

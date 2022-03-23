<?php

namespace App\Orchid\Resources;
use Orchid\Crud\ResourceRequest;
use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Illuminate\Validation\Rule;
use Orchid\Screen\Sight;
use App\Models\Category;
use Orchid\Screen\Fields\Quill;

class PostResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Post::class;

    /**
     * Get the fields displayed by the resource.
     * Ovo su polja koja se pojavljuju u create formi
     *
     * @return array
     */
    public function fields(): array
    {
    return [
        Input::make('title')
            ->title('Title')
            ->placeholder('Enter title here'),
        
           
            Select::make('category_id')
                ->fromModel(Category::class, 'type')
                //->multiple()
                ->title(__('kategoija posta'))
                ->help('Odaberi kategoriju posta'),
        
         Quill::make('body')
                ->title('Body')
                ->required()
                ->placeholder('Insert text here ...')
                ->help('Add the content for the message that you would like to send.')
                ,
      //  Input::make('category_id')
       //     ->title('Kategorija')
       //     ->placeholder('Unesi filtriranu kategoriju'),  //TODO napravi category filter
    ];
    }

    /**
     * Get the columns displayed by the resource.
     * Polja koja se pojavljuju u index tablici
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id'),
            TD::make('title')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            
            //TD::make('category_id')   //ovo mi sam o vraća FK što mi nije dovoljno
            TD::make('category_id', 'Rubrika')
            ->sort()
                ->render(function ($model) {
                    return $model->category()->get()->first()->type;
                }),
                        
            TD::make('created_at', 'Date of creation')
            ->sort()
                ->render(function ($model) {
                    return $model->created_at->toDateTimeString();
                }),
                        

            TD::make('updated_at', 'Update date')
                ->render(function ($model) {
                    return $model->updated_at->toDateTimeString();
                })
       ];
    }

    /**
 * Action to create and update the model
 *
 * @param ResourceRequest $request
 * @param Model           $model
 */
public function onSave(ResourceRequest $request, Model $model)
{
    $model->forceFill($request->all())->save();
}

/**
 * Action to delete a model
 *
 * @param Model $model
 *
 * @throws Exception
 */
public function onDelete(Model $model)
{
    $model->delete();
}
    
    
    /**
     * Get the sights displayed by the resource.
     * Polja koja se pojavljuju u SHOW view-u
     *
     * @return Sight[]
     */
    public function legend(): array
    {
           return [
        Sight::make('id'),
        Sight::make('title'),
        Sight::make('body')    // što će se dogoditi s HTML chars??
               ->render(function ($model) {
                    return $model->body;
                })
                   ,
        Sight::make('category_id'),    //TODO dodaj relacije i zamjeni ovo polje sa modelom Category   
        Sight::make('category_id', 'Rubrika')
                   ->render(function ($model) {
                    return $model->category()->get()->first()->type;
                }),
    ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(): array
    {
            return [];
    }
    public function rules(Model $model): array
{
    return [
        'title' => [
            'required',
            Rule::unique(self::$model, 'title')->ignore($model),
        ],
    ];
    }
}

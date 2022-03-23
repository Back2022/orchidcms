<?php

namespace App\Orchid\Resources;

use Orchid\Crud\ResourceRequest;
use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;
use Illuminate\Validation\Rule;
use Orchid\Screen\Sight;

class CategoryResource extends Resource {

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Category::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array {
        return [
                    Input::make('type')
                    ->title('Kategorija')
                    ->placeholder('Enter title here'),
        ];
    }

    /**
     * Get the columns displayed by the resource.
     * Polja koja se pojavljuju u index tablici
     * Ovo su polja koja se pojavljuju u create formi
     * 
     * @return TD[]
     */
    public function columns(): array {
        return [
            TD::make('id'),
            
            TD::make('type')
            ->sort()
            ->filter(TD::FILTER_TEXT),
            
            TD::make('created_at', 'Date of creation')
            ->render(function ($model) {
                return $model->created_at->toDateTimeString();
            }),
                    
            TD::make('updated_at', 'Update date')
            ->render(function ($model) {
                return $model->updated_at->toDateTimeString();
            }),
        ];
    }

    /**
     * Action to create and update the model
     *
     * @param ResourceRequest $request
     * @param Model           $model
     */
    public function onSave(ResourceRequest $request, Model $model) {
        $model->forceFill($request->all())->save();
    }

    /**
     * Action to delete a model
     *
     * @param Model $model
     *
     * @throws Exception
     */
    public function onDelete(Model $model) {
        $model->delete();
    }

    /**
     * Get the sights displayed by the resource.
     *  SHOW prikaz :)
     * 
     * @return Sight[]
     */
    public function legend(): array {
        return [
            Sight::make('id'),
            Sight::make('type'),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(): array {
        return [];
    }

}

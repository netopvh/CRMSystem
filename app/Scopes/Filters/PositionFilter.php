<?php

namespace App\Scopes\Filters;

trait PositionFilter
{
    // Фильтрация по городу
    public function scopePositionFilter($query, $request, $relations = null)
    {

    	if($relations == null){

	        //Фильтруем по списку городов
	        if($request->position_id){
	          $query = $query->whereIn('position_id', $request->position_id);
	        };

    	} else {

	        if($request->position_id){
	        	// dd($request->position_id);

				$query = $query->whereHas('staffer', function ($query) use ($request) {
				    $query = $query->whereIn('position_id', $request->position_id);
				})->orWhereNull('staffer_id');
		    };

    	};

      return $query;
    }

}

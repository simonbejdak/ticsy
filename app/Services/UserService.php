<?php

namespace App\Services;

use App\Enums\ResolverPanelOption;
use App\Enums\ResolverPanelTab;
use App\Models\FavoriteResolverPanelOption;
use App\Models\User;

class UserService
{
    static function switchFavoriteResolverPanelOption(User $user, ResolverPanelOption $option): void
    {
        if($user->hasFavoriteResolverPanelOption($option)){
            $favoriteOption = $user->getFavoriteResolverPanelOption($option);
            $favoriteOption->delete();
        } else {
            $favoriteOption = new FavoriteResolverPanelOption();
            $favoriteOption->user_id = $user->id;
            $favoriteOption->option = $option;
            $favoriteOption->save();
        }
    }

    static function setSelectedResolverPanelTab(User $user, ResolverPanelTab $tab): void
    {
         $user->selected_resolver_panel_tab = $tab;
         $user->save();
    }
}

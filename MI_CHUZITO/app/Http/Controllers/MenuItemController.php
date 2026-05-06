<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Http\Requests\MenuItemRequest;
use Inertia\Inertia;

class MenuItemController extends Controller
{
    public function index()
    {
        return Inertia::render('MenuItems/Index', [
            'items' => MenuItem::latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('MenuItems/Create');
    }

    public function store(MenuItemRequest $request)
    {
        MenuItem::create($request->validated());
        return redirect()->route('menu-items.index')
            ->with('success', 'Ítem creado correctamente.');
    }

    public function edit(MenuItem $menuItem)
    {
        return Inertia::render('MenuItems/Edit', [
            'item' => $menuItem,
        ]);
    }

    public function update(MenuItemRequest $request, MenuItem $menuItem)
    {
        $menuItem->update($request->validated());
        return redirect()->route('menu-items.index')
            ->with('success', 'Ítem actualizado.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();
        return redirect()->route('menu-items.index')
            ->with('success', 'Ítem eliminado.');
    }
}
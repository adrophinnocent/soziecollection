<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertHomepageSlideRequest;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomepageSlideController extends Controller
{
    public function store(UpsertHomepageSlideRequest $request): RedirectResponse
    {
        $mobileImagePath = $this->storeOptionalMobileImage($request);

        if ($mobileImagePath === false) {
            return back()->withErrors(['mobile_image' => 'The mobile design could not be stored.'])->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        if (empty($imagePath)) {
            if ($mobileImagePath) {
                Storage::disk('public')->delete($mobileImagePath);
            }

            return back()->withErrors(['image' => 'Upload a desktop design image file or enter an image URL.'])->withInput();
        }

        $attributes = Arr::except($request->validated(), ['image', 'image_url', 'mobile_image', 'mobile_image_url']);
        $attributes['image'] = $imagePath;
        $attributes['is_active'] = $request->boolean('is_active');
        $attributes['show_in_hero'] = $request->boolean('show_in_hero');
        $attributes['show_in_gallery'] = $request->boolean('show_in_gallery');

        if ($mobileImagePath) {
            $attributes['mobile_image'] = $mobileImagePath;
        } elseif ($request->filled('mobile_image_url')) {
            $attributes['mobile_image'] = $request->input('mobile_image_url');
        }

        Banner::create($attributes);

        return redirect()->route('admin.content')->with('success', 'Homepage slide created successfully.');
    }

    public function update(UpsertHomepageSlideRequest $request, Banner $banner): RedirectResponse
    {
        $mobileImagePath = $this->storeOptionalMobileImage($request);

        if ($mobileImagePath === false) {
            return back()->withErrors(['mobile_image' => 'The mobile design could not be stored.'])->withInput();
        }

        $attributes = Arr::except($request->validated(), ['image', 'image_url', 'mobile_image', 'mobile_image_url']);
        $attributes['is_active'] = $request->boolean('is_active');
        $attributes['show_in_hero'] = $request->boolean('show_in_hero');
        $attributes['show_in_gallery'] = $request->boolean('show_in_gallery');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            if ($imagePath) {
                $this->deleteStoredFile($banner->image);
                $attributes['image'] = $imagePath;
            }
        } elseif ($request->filled('image_url')) {
            $attributes['image'] = $request->input('image_url');
        }

        if ($mobileImagePath) {
            $this->deleteStoredFile($banner->mobile_image);
            $attributes['mobile_image'] = $mobileImagePath;
        } elseif ($request->filled('mobile_image_url')) {
            $attributes['mobile_image'] = $request->input('mobile_image_url');
        }

        $banner->update($attributes);

        return redirect()->route('admin.content')->with('success', 'Homepage slide updated successfully.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->deleteStoredFile($banner->image);
        $this->deleteStoredFile($banner->mobile_image);

        $banner->delete();

        return redirect()->route('admin.content')->with('success', 'Homepage slide deleted successfully.');
    }

    private function storeOptionalMobileImage(UpsertHomepageSlideRequest $request): string|false|null
    {
        if (! $request->hasFile('mobile_image')) {
            return null;
        }

        return $request->file('mobile_image')->store('banners/mobile', 'public');
    }

    private function deleteStoredFile(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['http://', 'https://'])) {
            Storage::disk('public')->delete($path);
        }
    }
}

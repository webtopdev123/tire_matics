<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

use App\Models\FleetConfiguration;
use App\Models\Merchant;

class FleetConfigurationController extends Controller {
  
  public function index() {
    $merchants = Merchant::whereNull('deleted_at')->get();

    $data = [
      'merchants' => $merchants,
    ];

    return view('content.fleet-configuration.fleet-configuration', $data);
  }

    public function list(Request $request) {
      $query = FleetConfiguration::whereNull('deleted_at')
      ->orderBy('configuration_type')
      ->orderBy('configuration_name');
      
      $list = $query->get();
      
      return response()->json([
        'data' => $list,
      ]);
    }
    
    public function create(Request $request) {

      if ($request->hasFile('configuration_photo')) {
        $image = $request->file('configuration_photo');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/configuration'), $imageName);

        // Resize the image if needed
        $tempImage = Image::make(public_path('uploads/configuration/' . $imageName));
        if ($tempImage->width() > 400 || $tempImage->height() > 400) {
            $tempImage->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $tempImage->save(public_path('uploads/configuration/' . $imageName));
        }
      }

      FleetConfiguration::create([
        'configuration_name' => $request->input('configuration_name'),
        'configuration_type' => $request->input('configuration_type'),
        'configuration_setting' => $request->input('configuration_setting'),
        'configuration_photo' => $imageName,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
      
      return response()->json(['message' => 'FleetConfiguration created successfully', 'status' => 'success']);
    }
    
    public function update(Request $request) {
      $user = $request->user();
        
      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }
  
      FleetConfiguration::where('configuration_id', $request->input('configuration_id'))
      ->update([
        'configuration_name' => $request->input('configuration_name'),
        'configuration_type' => $request->input('configuration_type'),
        'configuration_setting' => $request->input('configuration_setting'),
        'updated_at' => now(),
      ]);

      if ($request->hasFile('configuration_photo')) {
        $image = $request->file('configuration_photo');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/configuration'), $imageName);

        // Resize the image if needed
        $tempImage = Image::make(public_path('uploads/configuration/' . $imageName));
        if ($tempImage->width() > 400 || $tempImage->height() > 400) {
            $tempImage->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $tempImage->save(public_path('uploads/configuration/' . $imageName));
        }

        FleetConfiguration::where('configuration_id', $request->input('configuration_id'))
        ->update([
          'configuration_photo' => $imageName,
        ]);

      }

    }

    public function delete(Request $request) {
      $user = $request->user();
      
      if (!$user) {
        return response()->json(['message' => 'User not found'], 405);
      }

      FleetConfiguration::where('configuration_id', $request->input('id'))
      ->update(['deleted_at' => now()]);

      $config = FleetConfiguration::where('configuration_id', $request->input('id'))->first();

      if (isset($config->configuration_photo)) {
        $filePath = public_path('uploads/configuration/' . $config->configuration_photo);
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }
      }
      
      return response()->json();
    }

}

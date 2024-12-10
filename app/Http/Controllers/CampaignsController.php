<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignRequest;
use App\Models\Campaign;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CampaignsController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::get();
        return view('admin.campaign.index', compact(['campaigns']));
    }
    public function create()
    {
        return view('admin.campaign.form');
    }

    public function insert(CampaignRequest $request)
    {

        $campaign = new Campaign;
        $campaign->name = $request->name;
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->status = $request->status;
        $campaign->save();

        toastr()->success('Brand Created Successfully!');
        return redirect()->route('campaigns');
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.campaign.form', compact(['campaign']));
    }

    public function update(CampaignRequest $request, Campaign $campaign)
    {

        $campaign->name = $request->name;
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->status = $request->status;
        $campaign->save();

        toastr()->success('Campaign Updated Successfully!');
        return redirect()->route('campaigns');
    }

    public function delete(Campaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('campaigns')->with('flash_success', 'Campaign deleted successfully.');
    }

    public function products($id)
    {
        $campaign = Campaign::with('products')->find($id);
        $products = Product::where('status', 1)->get();
        $existing_products = $campaign->products->pluck('id');
        return view('admin.campaign.products', compact(['campaign', 'products', 'existing_products']));
    }

    public function productsAction($id, Request $request)
    {
        $campaign = Campaign::with('products')->find($id);
        $current_products_ids = $campaign->products->pluck('id')->toArray();
        $sync_values = [];
        foreach ($request->products as $product_id) {
            if (!in_array($product_id, $current_products_ids)) {
                $product = Product::find($product_id);
                $sync_values[$product->id] = ['campaign_price' => $product->price];
            }
        }
        $campaign->products()->syncWithoutDetaching($sync_values);
        return redirect()->route('campaigns.products', $campaign->id)->with('flash_success', 'Products Synced Successfully.');
    }

    public function productDelete(Campaign $campaign, Product $product)
    {
        $campaign->products()->wherePivot('product_id', '=', $product->id)->detach();
        return redirect()->route('campaigns.products', $campaign->id)
            ->with('flash_success', 'Product Removed Successfully.');
    }

    public function updateDiscount(Request $request)
    {
        $this->validate($request, [
            'campaign_id' => 'required',
            'product_id' => 'required',
            'campaign_price' => 'required',
        ]);
        $campaign = Campaign::find($request->campaign_id);
        $campaign->products()
            ->updateExistingPivot($request->product_id, ['campaign_price' => $request->campaign_price]);
        return [
            "message" => "Product Updated Successfully"
        ];
    }
}

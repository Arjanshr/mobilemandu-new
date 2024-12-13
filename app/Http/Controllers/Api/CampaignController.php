<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActiveCampaignResource;
use App\Http\Resources\CampaignProductResource;
use App\Http\Resources\ContentResource;
use App\Http\Resources\ExpiredCampaignResource;
use App\Http\Resources\FutureCampaignResource;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CampaignController extends BaseController
{
    public function getCampaigns($status)
    {
        if ($status == 'active') {
            $campaigns = Campaign::running()->get();
            return $this->sendResponse(ActiveCampaignResource::collection($campaigns), 'Campaigns retrieved successfully.');
        }

        if ($status == 'expired') {
            $campaigns = Campaign::expired()->get();
            return $this->sendResponse(ExpiredCampaignResource::collection($campaigns), 'Campaigns retrieved successfully.');
        }

        if ($status == 'future') {
            $campaigns = Campaign::notStarted()->get();
            return $this->sendResponse(FutureCampaignResource::collection($campaigns), 'Campaigns retrieved successfully.');
        }

    }

    public function getCampaignProducts(Campaign $campaign)
    {
        $products = $campaign->products()->published()->get();
        return $this->sendResponse(CampaignProductResource::collection($products)->resource, 'Campaign products retrieved successfully.');

    }
}

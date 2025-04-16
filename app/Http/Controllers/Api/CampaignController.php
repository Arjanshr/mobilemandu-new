<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignResource;
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
        switch ($status) {
            case 'active':
                $campaigns = Campaign::running()->orderBy('display_order', 'asc')->get(); // Order by display_order
                break;

            case 'expired':
                $campaigns = Campaign::expired()->orderBy('display_order', 'asc')->get(); // Order by display_order
                break;

            case 'future':
                $campaigns = Campaign::notStarted()->orderBy('display_order', 'asc')->get(); // Order by display_order
                break;

            default:
                return $this->sendError('Invalid campaign status', 400);
        }
        // return $campaigns;
        return $this->sendResponse(CampaignResource::collection($campaigns), 'Campaigns retrieved successfully.');
    }

    
    public function getCampaignProducts(Campaign $campaign)
    {
        $products = $campaign->products()->published()->get();
        return $this->sendResponse(CampaignProductResource::collection($products)->resource, 'Campaign products retrieved successfully.');
    }
}

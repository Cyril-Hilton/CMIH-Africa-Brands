<?php

namespace App\Mail;

use App\Models\Brand;
use App\Models\BrandConsumerEntry;
use App\Services\BarcodeGeneratorService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConsumerDiscountMail extends Mailable
{
    use Queueable, SerializesModels;

    public Brand $brand;
    public BrandConsumerEntry $entry;
    public string $discountPercentage;
    public string $barcodeSvg;

    public function __construct(Brand $brand, BrandConsumerEntry $entry, string $discountPercentage = '20% OFF')
    {
        $this->brand = $brand;
        $this->entry = $entry;
        $this->discountPercentage = $discountPercentage;
        $this->barcodeSvg = BarcodeGeneratorService::generateSvg($entry->reward_code ?: 'CMIH-REWARD', 300, 90);
    }

    public function build()
    {
        $displayName = $this->brand->display_name ?: $this->brand->name;

        return $this->subject("Your {$displayName} Discount Code & Barcode")
            ->html($this->renderHtml());
    }

    private function renderHtml(): string
    {
        $displayName = htmlspecialchars($this->brand->display_name ?: $this->brand->name);
        $code = htmlspecialchars($this->entry->reward_code);
        $name = htmlspecialchars($this->entry->name);
        $discount = htmlspecialchars($this->discountPercentage);

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>{$displayName} Reward</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #f4f4f6; margin: 0; padding: 20px; color: #222;'>
            <div style='max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 30px; border: 1px solid #e0e0e0; box-shadow: 0 10px 30px rgba(0,0,0,0.06);'>
                <div style='text-align: center; margin-bottom: 20px;'>
                    <h2 style='color: #ff1020; margin: 0; font-size: 24px; text-transform: uppercase;'>{$displayName}</h2>
                    <p style='color: #666; font-size: 13px; margin-top: 4px;'>Official Brand Activation Offer</p>
                </div>

                <div style='background: #fff0f2; border: 2px dashed #ff1020; border-radius: 14px; padding: 20px; text-align: center; margin: 20px 0;'>
                    <span style='display: inline-block; background: #ff1020; color: #ffffff; font-weight: bold; font-size: 14px; padding: 6px 14px; border-radius: 20px; text-transform: uppercase; margin-bottom: 12px;'>
                        {$discount}
                    </span>
                    <h3 style='margin: 0; font-size: 14px; color: #555; text-transform: uppercase;'>Your Exclusive Redemption Code</h3>
                    <div style='font-size: 22px; font-weight: bold; font-family: monospace; color: #111; margin: 10px 0; letter-spacing: 2px;'>
                        {$code}
                    </div>
                </div>

                <div style='text-align: center; margin: 25px 0;'>
                    <p style='font-size: 12px; color: #555; margin-bottom: 10px;'>Present this barcode at any participating retail outlet:</p>
                    <div style='background: #fff; padding: 12px; border: 1px solid #ddd; border-radius: 10px; display: inline-block;'>
                        {$this->barcodeSvg}
                    </div>
                </div>

                <div style='font-size: 12px; color: #777; line-height: 1.5; border-top: 1px solid #eee; padding-top: 15px; margin-top: 20px;'>
                    <p style='margin: 0;'>Hello <strong>{$name}</strong>, thank you for participating in the <strong>{$displayName}</strong> activation.</p>
                    <p style='margin: 5px 0 0;'>You can show this email or barcode directly on your phone to the retail attendant to redeem your offer.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}

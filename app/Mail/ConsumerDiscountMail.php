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

        $primaryColor = $this->brand->public_primary_color ?: $this->brand->primary_color ?: '#00205b';
        $secondaryColor = $this->brand->public_secondary_color ?: $this->brand->secondary_color ?: '#00a3e0';

        // Base64 encode SVG for 100% email client compatibility (Gmail, Outlook, Apple Mail)
        $barcodeBase64 = 'data:image/svg+xml;base64,'.base64_encode($this->barcodeSvg);

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>{$displayName} Reward</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #f4f5f8; margin: 0; padding: 20px; color: #222;'>
            <div style='max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.06);'>
                <div style='text-align: center; margin-bottom: 20px;'>
                    <h2 style='color: {$primaryColor}; margin: 0; font-size: 26px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;'>{$displayName}</h2>
                    <p style='color: #64748b; font-size: 13px; margin-top: 4px;'>Official Brand Activation Offer</p>
                </div>

                <div style='background: #f8fafc; border: 2px dashed {$primaryColor}; border-radius: 14px; padding: 24px; text-align: center; margin: 20px 0;'>
                    <span style='display: inline-block; background: {$primaryColor}; color: #ffffff; font-weight: bold; font-size: 14px; padding: 7px 16px; border-radius: 20px; text-transform: uppercase; margin-bottom: 14px; box-shadow: 0 4px 10px rgba(0,0,0,0.15);'>
                        {$discount}
                    </span>
                    <h3 style='margin: 0; font-size: 13px; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;'>Your Exclusive Redemption Code</h3>
                    <div style='font-size: 24px; font-weight: 800; font-family: monospace; color: {$primaryColor}; margin: 12px 0; letter-spacing: 3px;'>
                        {$code}
                    </div>
                </div>

                <div style='text-align: center; margin: 25px 0;'>
                    <p style='font-size: 12px; color: #64748b; margin-bottom: 12px; font-weight: 600;'>Present this barcode at any participating retail outlet:</p>
                    <div style='background: #ffffff; padding: 16px 20px; border: 1px solid #cbd5e1; border-radius: 12px; display: inline-block; box-shadow: 0 2px 8px rgba(0,0,0,0.04);'>
                        <img src='{$barcodeBase64}' alt='Barcode {$code}' width='300' height='90' style='display: block; margin: 0 auto; max-width: 100%; height: auto;' />
                    </div>
                </div>

                <div style='font-size: 12px; color: #64748b; line-height: 1.6; border-top: 1px solid #e2e8f0; padding-top: 18px; margin-top: 25px;'>
                    <p style='margin: 0;'>Hello <strong>{$name}</strong>, thank you for participating in the <strong>{$displayName}</strong> activation.</p>
                    <p style='margin: 6px 0 0;'>You can show this email or barcode directly on your phone to the retail attendant to redeem your offer.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}

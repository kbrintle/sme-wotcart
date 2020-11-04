<table style="width:100%;">
    <tbody>
    <tr>
        <td style="padding: 30px">
            <h2 style="color: #2196F3; font-size: 24px;">Your order is confirmed!</h2>
            <p style="line-height: 1.5; color: #77909C; font-size: 12px; letter-spacing: 1px;">Hi {{customer_firstname}},
            </p>
        </td>
    </tr>
    <tr style="background: #eceff1;">
        <td style="padding: 15px 30px;">
            <span style="float: left; padding: 12px 0;">Order: <a href="" style="color: #2196F3;">#{{order_id}}</a></span>
            <!--            <a href="#" style="text-decoration: none; float: right; font-size: 16px; color: #FFF; background: #2196F3; border-radius: 4px; padding: 10px 30px;">-->
            <!--                Manage Order-->
            <!--            </a>-->
        </td>
    </tr>
    <tr>
        <td style="padding: 30px;">

            <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                {{#each items}}
                <tr>
                    <td style="padding: 15px 0; color: #77909C; font-size: 12px;">
                        <img src="{{this.image}}">
                    </td>
                    <td style="padding: 15px 0; color: #77909C; font-size: 12px;">
                        <div style="color: #2196F3; font-size: 16px;">{{this.name}}</div>
                    </td>
                    <td style="padding: 15px 0; color: #77909C; font-size: 18px; text-align: right;">
                        {{this.qty}}
                    </td>
                    <td style="padding: 15px 0; color: #77909C; font-size: 18px; text-align: right;">
                        {{this.item_price}}
                    </td>
                    <td style="padding: 15px 0; color: #77909C; font-size: 18px; text-align: right;">
                        {{this.item_subtotal}}
                    </td>
                </tr>
                {{/each}}

                <tr style="border-top:solid 1px #F5F7F8;">
                    <td></td>
                    <td style="padding: 15px 0;">
                        <span style="margin-bottom: 15px; font-size: 16px; color: #77909C; display: block; text-align: right; padding-right: 30px">Subtotal</span>
                        <span style="margin-bottom: 15px; font-size: 16px; color: #77909C; display: block; text-align: right; padding-right: 30px">+ Tax</span>

                        <span style="font-weight: bold; margin-bottom: 15px; font-size: 16px; color: #004270; display: block; text-align: right; padding-right: 30px">Total</span>
                    </td>
                    <td style="padding: 15px 0;">
                        <span style="margin-bottom: 15px; font-size: 16px; color: #77909C; display: block; text-align: right;">${{subtotal}}</span>
                        <span style="margin-bottom: 15px; font-size: 16px; color: #77909C; display: block; text-align: right;"> ${{tax}}</span>


                        <span style="font-weight: bold; margin-bottom: 15px; font-size: 16px; color: #004270; display: block; text-align: right;">${{grand_total}}</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <p style="line-height: 1.5; color: #77909C; font-size: 12px; letter-spacing: 1px;">This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message.</p>
        </td>
    </tr>
    </tbody>
</table>
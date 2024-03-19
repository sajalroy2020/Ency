SET FOREIGN_KEY_CHECKS=0;

INSERT INTO `currencies` (`id`, `currency_code`, `symbol`, `currency_placement`, `current_currency`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'USD', '$', 'before', 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(2, 'BDT', '৳', 'before', 0, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(3, 'INR', '₹', 'before', 0, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(4, 'GBP', '£', 'after', 0, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(5, 'MXN', '$', 'before', 0, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(6, 'SAR', 'SR', 'before', 0, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22');


INSERT INTO `email_templates` (`id`, `user_id`, `tenant_id`, `category`, `title`, `slug`, `subject`, `body`, `default`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'email-verify', 'Email Verification', 'email-verification', 'Verify Your Account', '<p>Hello, {{username}}\n            </p><p>            Thank you for creating an account with us. We\'re excited to have you as a part of our community!\n\n                Before you can start using your account, we need to verify your email address. Please click on the link below to complete the verification process:\n            </p><p>\n\n                Link: {{email_verify_url}}\n                        </p>', 1, 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(2, 1, NULL, 'reset-password', 'Password Reset', 'password-reset', 'Reset your password', '<div><b>Hello</b> ,{{username}}</div><div><br></div><div>we\'re sending you this email because you requested a password reset. Click on this link to create a new password.</div><div><br></div><div>Set a new password . Here is a link -</div><div><br></div><div>Link :&nbsp;<span style=\"background-color: rgb(209, 231, 221); color: rgb(15, 81, 50); font-family: inter, sans-serif; text-align: var(--bs-body-text-align);\">{{<b>reset_password_url</b>}}</span></div><div><br></div><div>If you didn\'t request a password reset, you can ignore this email. Your password will not be a changed.</div>', 1, 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(3, 2, 'zainiklab', 'ticket', 'Ticket Create Notify For Client', 'ticket-create-notify-for-client', 'New Ticket Created - {{tracking_no}}', '<p><b>Dear</b> {{username}},\n            </p><p>\n                            We are happy to inform you that a new ticket has been successfully created in our system with the following details:\n            </p><p>\n                            Tracking No: <b>{{tracking_no}}\n            </p><p>                Date Created: {{ticket_created_time}}\n            </p><p>                Title: {{ticket_title}}</p><p>\n                            You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal.\n\n                            If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}}  or {{contact_phone}}.\n\n                            Thank you for using our services!\n            </p><p><b>\n                Best regards</b>,\n                            {{app_name}}</p>', 1, 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(4, 2, 'zainiklab', 'ticket', 'Ticket Create Notify For Admin', 'ticket-create-notify-for-admin', 'New Ticket Created - {{tracking_no}}', '<p><b>Dear</b> {{username}},\n            </p><p>\n                            We are happy to inform you that a new ticket has been successfully created in our system with the following details:\n            </p><p>\n                            Tracking No: <b>{{tracking_no}}\n            </p><p>                Date Created: {{ticket_created_time}}\n            </p><p>                Title: {{ticket_title}}</p><p>\n                            You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal.\n\n                            If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}}  or {{contact_phone}}.\n\n                            Thank you for using our services!\n            </p><p><b>\n                Best regards</b>,\n                            {{app_name}}</p>', 1, 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(5, 2, 'zainiklab', 'ticket', 'Ticket Create Notify For Team Member', 'ticket-create-notify-for-team-member', 'New Ticket Created - {{tracking_no}}', '<p><b>Dear</b> {{username}},\n            </p><p>\n                            We are happy to inform you that a new ticket has been successfully created in our system with the following details:\n            </p><p>\n                            Tracking No: <b>{{tracking_no}}\n            </p><p>                Date Created: {{ticket_created_time}}\n            </p><p>                Title: {{ticket_title}}</p><p>\n                            You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal.\n\n                            If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}}  or {{contact_phone}}.\n\n                            Thank you for using our services!\n            </p><p><b>\n                Best regards</b>,\n                            {{app_name}}</p>', 1, 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(6, 2, 'zainiklab', 'ticket', 'Ticket Conversation For Admin', 'ticket-conversation-for-admin', 'New Reply For Your Ticket -{{tracking_no}}', '<div><span style=\"font-weight: bolder;\">Dear</span>&nbsp;{{username}},</div><div><br></div><div>A new ticket has been created in our system. Ticket Tracking No: {{tracking_no}} with the following details:</div><div>Date Created: {{ticket_created_time}}&nbsp;</div><div><br></div><div>Title: {{ticket_title}}&nbsp;</div><div><br></div><div>Thank you for your attention.</div><div><span style=\"font-weight: bolder;\">Best regards,</span>&nbsp;{{app_name}}</div>', 1, 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(7, 2, 'zainiklab', 'ticket', 'Ticket Conversation For Team Member', 'ticket-conversation-for-team-member', 'New Reply For Your Ticket - {{tracking_no}}', '<div><span style=\"font-weight: bolder;\">Dear</span>&nbsp;{{username}},</div><div><br></div><div>We are happy to inform you that your Tracking No: {{tracking_no}} has reply in our system with the following details:&nbsp;</div><div>Date Created: {{ticket_created_time}}&nbsp;</div><div><br></div><div>Title: {{ticket_title}}&nbsp;</div><div><br></div><div>Thank you for your attention.</div><div><span style=\"font-weight: bolder;\">Best regards</span>, {{app_name}}</div>', 1, 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(8, 2, 'zainiklab', 'ticket', 'Ticket Conversation For Client', 'ticket-conversation-for-client', 'New Reply For Your Ticket - {{tracking_no}}', '<div><span style=\"font-weight: bolder;\">Dear</span>&nbsp;{{username}},</div><div><br></div><div>We are happy to inform you that your Tracking No: {{tracking_no}} has reply in our system with the following details:&nbsp;</div><div>Date Created: {{ticket_created_time}}&nbsp;</div><div><br></div><div>Title: {{ticket_title}}&nbsp;</div><div><br></div><div>You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal. If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}} or {{contact_phone}}. Thank you for using our services!</div><div><br></div><div><span style=\"font-weight: bolder;\">Best regards</span>, {{app_name}}</div>', 1, 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(9, 2, 'zainiklab', 'ticket', 'Ticket Status Change For Client', 'ticket-status-change-for-client', 'Ticket Status Changed - {{tracking_no}}', '<div><span style=\"font-weight: bolder;\">Dear</span>&nbsp;{{username}},</div><div><br></div><div>We are happy to inform you that your Tracking No: {{tracking_no}} has ticket status change in our system with the following details:&nbsp;</div><div>Date Created: {{ticket_created_time}}&nbsp;</div><div><br></div><div>Title: {{ticket_title}}&nbsp;</div><div><br></div><div>You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal. If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}} or {{contact_phone}}. Thank you for using our services!</div><div><br></div><div><span style=\"font-weight: bolder;\">Best regards</span>, {{app_name}}</div>', 1, 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(10, 2, 'zainiklab', 'ticket', 'Ticket assign For Team Member', 'ticket-assign-for-team-member', 'ticket assign', 'ticket asaingn', 1, 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(11, 2, 'zainiklab', 'quotation', 'Quotation Email Send', 'quotation-email-send', 'ticket assign', 'ticket asaingn', 1, 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(12, 2, 'zainiklab', 'invoice', 'Invoice Unpaid Notify For Client', 'invoice-unpaid-notify-for-client', 'Invoice Unpaid Notify For Client', 'Invoice Unpaid Notify For Client', 1, 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(13, 2, 'zainiklab', 'invoice', 'Invoice Paid Notify For Client', 'invoice-paid-notify-for-client', 'Invoice Paid Notify For Client', 'Invoice Paid Notify For Client', 1, 1, NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22');



INSERT INTO `gateways` (`id`, `user_id`, `tenant_id`, `title`, `slug`, `image`, `status`, `mode`, `url`, `key`, `secret`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, NULL, 'Paypal', 'paypal', 'assets/images/gateway-icon/paypal.png', 1, 2, '', '', '', NULL, NULL, NULL),
(2, 1, NULL, 'Stripe', 'stripe', 'assets/images/gateway-icon/stripe.png', 1, 2, '', '', '', NULL, NULL, NULL),
(3, 1, NULL, 'Razorpay', 'razorpay', 'assets/images/gateway-icon/razorpay.png', 1, 2, '', '', '', NULL, NULL, NULL),
(4, 1, NULL, 'Instamojo', 'instamojo', 'assets/images/gateway-icon/instamojo.png', 1, 2, '', '', '', NULL, NULL, NULL),
(5, 1, NULL, 'Mollie', 'mollie', 'assets/images/gateway-icon/mollie.png', 1, 2, '', '', '', NULL, NULL, NULL),
(6, 1, NULL, 'Paystack', 'paystack', 'assets/images/gateway-icon/paystack.png', 1, 2, '', '', '', NULL, NULL, NULL),
(7, 1, NULL, 'Sslcommerz', 'sslcommerz', 'assets/images/gateway-icon/sslcommerz.png', 1, 2, '', '', '', NULL, NULL, NULL),
(8, 1, NULL, 'Flutterwave', 'flutterwave', 'assets/images/gateway-icon/flutterwave.png', 1, 2, '', '', '', NULL, NULL, NULL),
(9, 1, NULL, 'Mercadopago', 'mercadopago', 'assets/images/gateway-icon/mercadopago.png', 1, 2, '', '', '', NULL, NULL, NULL),
(10, 1, NULL, 'Bank', 'bank', 'assets/images/gateway-icon/bank.png', 1, 2, '', '', '', NULL, NULL, NULL),
(11, 1, NULL, 'Cash', 'cash', 'assets/images/gateway-icon/cash.png', 1, 2, '', '', '', NULL, NULL, NULL),
(12, 2, 'zainiklab', 'Paypal', 'paypal', 'assets/images/gateway-icon/paypal.png', 1, 2, '', '', '', NULL, NULL, NULL),
(13, 2, 'zainiklab', 'Stripe', 'stripe', 'assets/images/gateway-icon/stripe.png', 1, 2, '', '', '', NULL, NULL, NULL),
(14, 2, 'zainiklab', 'Razorpay', 'razorpay', 'assets/images/gateway-icon/razorpay.png', 1, 2, '', '', '', NULL, NULL, NULL),
(15, 2, 'zainiklab', 'Instamojo', 'instamojo', 'assets/images/gateway-icon/instamojo.png', 1, 2, '', '', '', NULL, NULL, NULL),
(16, 2, 'zainiklab', 'Mollie', 'mollie', 'assets/images/gateway-icon/mollie.png', 1, 2, '', '', '', NULL, NULL, NULL),
(17, 2, 'zainiklab', 'Paystack', 'paystack', 'assets/images/gateway-icon/paystack.png', 1, 2, '', '', '', NULL, NULL, NULL),
(18, 2, 'zainiklab', 'Sslcommerz', 'sslcommerz', 'assets/images/gateway-icon/sslcommerz.png', 1, 2, '', '', '', NULL, NULL, NULL),
(19, 2, 'zainiklab', 'Flutterwave', 'flutterwave', 'assets/images/gateway-icon/flutterwave.png', 1, 2, '', '', '', NULL, NULL, NULL),
(20, 2, 'zainiklab', 'Mercadopago', 'mercadopago', 'assets/images/gateway-icon/mercadopago.png', 1, 2, '', '', '', NULL, NULL, NULL),
(21, 2, 'zainiklab', 'Bank', 'bank', 'assets/images/gateway-icon/bank.png', 1, 2, '', '', '', NULL, NULL, NULL),
(22, 2, 'zainiklab', 'Cash', 'cash', 'assets/images/gateway-icon/cash.png', 1, 2, '', '', '', NULL, NULL, NULL);



INSERT INTO `gateway_currencies` (`id`, `gateway_id`, `user_id`, `tenant_id`, `currency`, `conversion_rate`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, NULL, 'USD', 1.00, NULL, NULL, NULL),
(2, 2, 1, NULL, 'USD', 1.00, NULL, NULL, NULL),
(3, 3, 1, NULL, 'INR', 80.00, NULL, NULL, NULL),
(4, 4, 1, NULL, 'INR', 80.00, NULL, NULL, NULL),
(5, 5, 1, NULL, 'USD', 1.00, NULL, NULL, NULL),
(6, 6, 1, NULL, 'NGN', 464.00, NULL, NULL, NULL),
(7, 7, 1, NULL, 'BDT', 100.00, NULL, NULL, NULL),
(8, 8, 1, NULL, 'NGN', 464.00, NULL, NULL, NULL),
(9, 9, 1, NULL, 'BRL', 5.00, NULL, NULL, NULL),
(10, 10, 1, NULL, 'USD', 1.00, NULL, NULL, NULL),
(11, 11, 1, NULL, 'USD', 1.00, NULL, NULL, NULL),
(12, 12, 2, 'zainiklab', 'USD', 1.00, NULL, NULL, NULL),
(13, 13, 2, 'zainiklab', 'USD', 1.00, NULL, NULL, NULL),
(14, 14, 2, 'zainiklab', 'INR', 80.00, NULL, NULL, NULL),
(15, 15, 2, 'zainiklab', 'INR', 80.00, NULL, NULL, NULL),
(16, 16, 2, 'zainiklab', 'USD', 1.00, NULL, NULL, NULL),
(17, 17, 2, 'zainiklab', 'NGN', 464.00, NULL, NULL, NULL),
(18, 18, 2, 'zainiklab', 'BDT', 100.00, NULL, NULL, NULL),
(19, 19, 2, 'zainiklab', 'NGN', 464.00, NULL, NULL, NULL),
(20, 20, 2, 'zainiklab', 'BRL', 5.00, NULL, NULL, NULL),
(21, 21, 2, 'zainiklab', 'USD', 1.00, NULL, NULL, NULL),
(22, 22, 2, 'zainiklab', 'USD', 1.00, NULL, NULL, NULL);



INSERT INTO `languages` (`id`, `language`, `iso_code`, `flag_id`, `font`, `rtl`, `status`, `default`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'English', 'en', NULL, NULL, 0, 1, 1, '2023-12-21 12:48:22', '2023-12-21 12:48:22', NULL);

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 2);


INSERT INTO `packages` (`id`, `name`, `slug`, `number_of_client`, `number_of_order`, `others`, `monthly_price`, `yearly_price`, `status`, `is_default`, `is_trail`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Trial', 'trial', 3, 5, NULL, 0.00, 0.00, 1, 0, 1, '2023-12-21 12:48:22', '2023-12-21 12:48:22', NULL);

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'service', 'web', NULL, NULL),
(2, 'clients', 'web', NULL, NULL),
(3, 'team-member', 'web', NULL, NULL),
(4, 'tickets', 'web', NULL, NULL),
(5, 'orders', 'web', NULL, NULL),
(6, 'invoice', 'web', NULL, NULL),
(7, 'quotation', 'web', NULL, NULL),
(8, 'settings-role-permission', 'web', NULL, NULL),
(9, 'settings-payment', 'web', NULL, NULL),
(10, 'settings-coupon', 'web', NULL, NULL),
(11, 'settings-designation', 'web', NULL, NULL),
(12, 'order-form', 'web', NULL, NULL),
(13, 'subscription', 'web', NULL, NULL),
(14, 'email-template', 'web', NULL, NULL);

INSERT INTO `roles` (`id`, `name`, `guard_name`, `user_id`, `tenant_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', 2, NULL, 1, '2023-12-21 12:48:22', '2023-12-21 12:48:22');


INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1);


INSERT INTO `settings` (`id`, `option_key`, `option_value`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'build_version', '1', NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(2, 'current_version', '1.0', NULL, '2023-12-21 12:48:22', '2023-12-21 12:48:22'),
(3, 'app_mail_status', '0', NULL, '2023-12-21 12:55:28', '2023-12-21 12:58:48'),
(4, 'google_analytics_status', '0', NULL, '2023-12-21 12:55:30', '2023-12-21 12:55:30'),
(5, 'cookie_status', '0', NULL, '2023-12-21 12:55:40', '2023-12-21 12:58:05'),
(6, 'app_preloader_status', '0', NULL, '2023-12-21 12:56:03', '2023-12-21 12:56:08'),
(7, 'app_color_design_type', '1', NULL, '2023-12-21 12:57:19', '2023-12-21 12:57:19'),
(8, 'app_primary_color', '#ff671b', NULL, '2023-12-21 12:57:19', '2023-12-21 12:57:19'),
(9, 'app_secondary_color', '#111111', NULL, '2023-12-21 12:57:19', '2023-12-21 12:57:19'),
(10, 'app_text_color', '#585858', NULL, '2023-12-21 12:57:19', '2023-12-21 12:57:19'),
(11, 'app_section_bg_color', '#fffaf7', NULL, '2023-12-21 12:57:19', '2023-12-21 12:57:19'),
(12, 'app_hero_bg_color1', '#000000', NULL, '2023-12-21 12:57:19', '2023-12-21 12:57:19'),
(13, 'app_hero_bg_color2', '#000000', NULL, '2023-12-21 12:57:19', '2023-12-21 12:57:19'),
(14, 'app_hero_bg_color', NULL, NULL, '2023-12-21 12:57:19', '2023-12-21 12:57:19'),
(19, 'app_name', 'Zaiagency', NULL, '2023-12-21 13:06:38', '2023-12-21 13:06:38'),
(20, 'app_email', 'Zaiagency@gmail.com', NULL, '2023-12-21 13:06:38', '2023-12-21 13:06:38'),
(21, 'app_contact_number', '(123-458-987254824185)', NULL, '2023-12-21 13:06:38', '2023-12-21 13:06:38'),
(22, 'app_location', '45/7 dreem street, albania dnobod, USA', NULL, '2023-12-21 13:06:38', '2023-12-21 13:06:38'),
(23, 'app_copyright', '© 2023 Zaigency. All Rights Reserved.', NULL, '2023-12-21 13:06:38', '2023-12-21 13:06:38'),
(24, 'app_footer_text', 'Zaigency revolutionizes project management, providing a robust platform facilitating seamless collaboration, streamlined order processing, and efficient service management. It offers a comprehensive suite of tools tailored to enhance project handling and optimize workflows.', NULL, '2023-12-21 13:06:38', '2023-12-21 13:06:38'),
(25, 'develop_by', 'Zaiagency', NULL, '2023-12-21 13:06:38', '2023-12-21 13:06:38'),
(26, 'app_timezone', 'UTC', NULL, '2023-12-21 13:06:38', '2023-12-21 13:06:38');


INSERT INTO `users` (`id`, `uuid`, `name`, `nick_name`, `email`, `mobile`, `country`, `state`, `city`, `zip_code`, `address`, `currency`, `company_name`, `company_designation`, `company_country`, `company_state`, `company_city`, `company_zip_code`, `company_address`, `company_phone`, `company_logo`, `email_verified_at`, `password`, `image`, `role`, `email_verification_status`, `phone_verification_status`, `google_auth_status`, `google2fa_secret`, `google_id`, `facebook_id`, `verify_token`, `otp`, `otp_expiry`, `last_seen`, `show_email_in_public`, `show_phone_in_public`, `created_by`, `status`, `tenant_id`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '12345', 'Super Administrator Doe', NULL, 'sadmin@gmail.com', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$PfNKc/T5jMaWDtLRcT1xgu3TGEQh7tdQljO5d2emqtzr3tc6RZl1K', NULL, 1, 1, 1, 0, '7ZCDJK57B4GJAMJ5', NULL, NULL, NULL, NULL, NULL, '2023-12-21 12:59:07', 1, 1, NULL, 1, NULL, NULL, NULL, NULL, '2023-12-21 12:54:07'),
(2, '123456', 'Administrator Doe', NULL, 'admin@gmail.com', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$fM4EHZdPv9P1e/s47TD5COiy02c..C1WKqaqe4JJRKq8EBEgJxwei', 5, 2, 1, 1, 0, 'Z2HEA7SLLSTYLYZT', NULL, NULL, NULL, NULL, NULL, '2023-12-21 15:27:45', 1, 1, NULL, 1, 'zainiklab', NULL, NULL, NULL, '2023-12-21 15:22:45');

INSERT INTO `user_packages` (`id`, `user_id`, `package_id`, `name`, `number_of_client`, `number_of_order`, `access_community`, `support`, `monthly_price`, `yearly_price`, `device_limit`, `start_date`, `end_date`, `order_id`, `status`, `is_trail`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 1, 'Trial', 999999, 999999, NULL, NULL, 0.00, 0.00, 0, '2023-12-21 12:48:22', '2023-12-26 12:48:22', NULL, 1, 1, '2023-12-21 12:48:22', '2023-12-21 12:48:22', NULL);

SET FOREIGN_KEY_CHECKS=1;

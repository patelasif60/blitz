<?php

return array (
  'accepted' => 'The :attribute must be accepted.',
  'active_url' => 'The :attribute is not a valid URL.',
  'after' => 'The :attribute must be a date after :date.',
  'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
  'alpha' => 'The :attribute must only contain letters.',
  'alpha_dash' => 'The :attribute must only contain letters, numbers, dashes and underscores.',
  'alpha_num' => 'The :attribute must only contain letters and numbers.',
  'array' => 'The :attribute must be an array.',
  'before' => 'The :attribute must be a date before :date.',
  'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
  'between' => 
  array (
    'numeric' => 'The :attribute must be between :min and :max.',
    'file' => 'The :attribute must be between :min and :max kilobytes.',
    'string' => 'The :attribute must be between :min and :max characters.',
    'array' => 'The :attribute must have between :min and :max items.',
  ),
  'boolean' => 'The :attribute field must be true or false.',
  'confirmed' => 'The :attribute confirmation does not match.',
  'current_password' => 'The password is incorrect.',
  'date' => 'The :attribute is not a valid date.',
  'date_equals' => 'The :attribute must be a date equal to :date.',
  'date_format' => 'The :attribute does not match the format :format.',
  'different' => 'The :attribute and :other must be different.',
  'digits' => 'The :attribute must be :digits digits.',
  'digits_between' => 'The :attribute must be between :min and :max digits.',
  'dimensions' => 'The :attribute has invalid image dimensions.',
  'distinct' => 'The :attribute field has a duplicate value.',
  'email' => 'The :attribute must be a valid email address.',
  'ends_with' => 'The :attribute must end with one of the following: :values.',
  'exists' => 'The selected :attribute is invalid.',
  'file' => 'The :attribute must be a file.',
  'filled' => 'The :attribute field must have a value.',
  'gt' => 
  array (
    'numeric' => 'The :attribute must be greater than :value.',
    'file' => 'The :attribute must be greater than :value kilobytes.',
    'string' => 'The :attribute must be greater than :value characters.',
    'array' => 'The :attribute must have more than :value items.',
  ),
  'gte' => 
  array (
    'numeric' => 'The :attribute must be greater than or equal :value.',
    'file' => 'The :attribute must be greater than or equal :value kilobytes.',
    'string' => 'The :attribute must be greater than or equal :value characters.',
    'array' => 'The :attribute must have :value items or more.',
  ),
  'image' => 'The :attribute must be an image.',
  'in' => 'The selected :attribute is invalid.',
  'in_array' => 'The :attribute field does not exist in :other.',
  'integer' => 'The :attribute must be an integer.',
  'ip' => 'The :attribute must be a valid IP address.',
  'ipv4' => 'The :attribute must be a valid IPv4 address.',
  'ipv6' => 'The :attribute must be a valid IPv6 address.',
  'json' => 'The :attribute must be a valid JSON string.',
  'lt' => 
  array (
    'numeric' => 'The :attribute must be less than :value.',
    'file' => 'The :attribute must be less than :value kilobytes.',
    'string' => 'The :attribute must be less than :value characters.',
    'array' => 'The :attribute must have less than :value items.',
  ),
  'lte' => 
  array (
    'numeric' => 'The :attribute must be less than or equal :value.',
    'file' => 'The :attribute must be less than or equal :value kilobytes.',
    'string' => 'The :attribute must be less than or equal :value characters.',
    'array' => 'The :attribute must not have more than :value items.',
  ),
  'max' => 
  array (
    'numeric' => 'The :attribute must not be greater than :max.',
    'file' => 'The :attribute must not be greater than :max kilobytes.',
    'string' => 'The :attribute must not be greater than :max characters.',
    'array' => 'The :attribute must not have more than :max items.',
  ),
  'mimes' => 'The :attribute must be a file of type: :values.',
  'mimetypes' => 'The :attribute must be a file of type: :values.',
  'min' => 
  array (
    'numeric' => 'The :attribute must be at least :min.',
    'file' => 'The :attribute must be at least :min kilobytes.',
    'string' => 'The :attribute must be at least :min characters.',
    'array' => 'The :attribute must have at least :min items.',
  ),
  'multiple_of' => 'The :attribute must be a multiple of :value.',
  'not_in' => 'The selected :attribute is invalid.',
  'not_regex' => 'The :attribute format is invalid.',
  'numeric' => 'The :attribute must be a number.',
  'password' => 'The password is incorrect.',
  'present' => 'The :attribute field must be present.',
  'regex' => 'The :attribute format is invalid.',
  'required' => 'The :attribute field is required.',
  'required_if' => 'The :attribute field is required when :other is :value.',
  'required_unless' => 'The :attribute field is required unless :other is in :values.',
  'required_with' => 'The :attribute field is required when :values is present.',
  'required_with_all' => 'The :attribute field is required when :values are present.',
  'required_without' => 'The :attribute field is required when :values is not present.',
  'required_without_all' => 'The :attribute field is required when none of :values are present.',
  'prohibited' => 'The :attribute field is prohibited.',
  'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
  'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
  'same' => 'The :attribute and :other must match.',
  'size' => 
  array (
    'numeric' => 'The :attribute must be :size.',
    'file' => 'The :attribute must be :size kilobytes.',
    'string' => 'The :attribute must be :size characters.',
    'array' => 'The :attribute must contain :size items.',
  ),
  'starts_with' => 'The :attribute must start with one of the following: :values.',
  'string' => 'The :attribute must be a string.',
  'timezone' => 'The :attribute must be a valid timezone.',
  'unique' => 'The :attribute has already been taken.',
  'uploaded' => 'The :attribute failed to upload.',
  'url' => 'The :attribute must be a valid URL.',
  'uuid' => 'The :attribute must be a valid UUID.',
  'custom' => 
  array (
    'attribute-name' => 
    array (
      'rule-name' => 'custom-message',
    ),
    'bankAccountHolderName' => 
    array (
      'max' => 'The bank name is required.',
      'required' => 'The bank account holder name is required.',
      'regex' => 'The bank account holder name can only contain letters and spaces.',
    ),
    'bankAccountNumber' => 
    array (
      'min' => 'The bank account number must be at least :min characters.',
      'required' => 'The bank account number is required.',
      'BuyerBankunique' => 'The bank account number is already exist.',
    ),
    'bankName' => 
    array (
      'required' => 'The bank name is required.',
    ),
    'isPrimary' => 
    array (
      'BuyerBankPrimaryExist' => 'Please check the primary bank option.',
    ),
    'roleName' => 
    array (
      'placeholder' => 'Enter role name',
      'required' => 'Role name is required.',
      'BuyerRoleNameUnique' => 'Role already exist.',
    ),
    'rolePermission' => 
    array (
      'required' => 'Please select at least one permission.',
    ),
    'email' => 
    array (
      'required' => 'The email is required',
      'email' => 'The email should be \'xyz@gmail.com\' format.',
    ),
    'firstName' => 
    array (
      'required' => 'The first name is required.',
    ),
    'lastName' => 
    array (
      'required' => 'The last name is required.',
    ),
    'phoneNumber' => 
    array (
      'required' => 'The phone number is required.',
      'min' => 'The phone number must be at least 9 characters.',
    ),
    'gender' => 
    array (
      'required' => 'The gender is required.',
    ),
    'maritalStatus' => 
    array (
      'required' => 'The marital status is required.',
    ),
    'religion' => 
    array (
      'required' => 'The religion is required.',
    ),
    'education' => 
    array (
      'required' => 'The education is required.',
    ),
    'occupation' => 
    array (
      'required' => 'The occupation is required.',
    ),
    'otherIncome' => 
    array (
      'required' => 'The other income is required.',
    ),
    'netSalary' => 
    array (
      'required' => 'The net salary is required.',
    ),
    'otherSourceOfIncome' => 
    array (
      'required' => 'The other source of income is required.',
    ),
    'ktpImage' => 
    array (
      'required' => 'The KTP Image is required.',
    ),
    'ktpSelfiImage' => 
    array (
      'required' => 'The KTP selfie Image is required.',
    ),
    'familyCardImage' => 
    array (
      'required' => 'The family card image is required.',
    ),
    'otherKtpImage' => 
    array (
      'required' => 'The other KTP Image is required.',
    ),
    'dateOfBirth' => 
    array (
      'required' => 'The date of birth is required.',
      'date' => 'The date of birth does not match the format dd-mm-YY.',
      'date_format' => 'The date of birth does not match the format dd-mm-YY.',
    ),
    'placeOfBirth' => 
    array (
      'required' => 'The place of birth is required.',
    ),
    'myPosition' => 
    array (
      'required' => 'The my position is required.',
    ),
    'otherFirstName' => 
    array (
      'required' => 'The other first name is required.',
    ),
    'otherLastName' => 
    array (
      'required' => 'The other last name is required.',
    ),
    'otherMemberPhone' => 
    array (
      'required' => 'The other member phone is required.',
      'min' => 'The phone number must be at least 9 characters.',
    ),
    'otherKtpNik' => 
    array (
      'required' => 'The other KTP NIK is required.',
      'min' => 'The KTP NIK number should be 16 number only.',
      'different' => 'Other Ktp Nik should not be same as like Ktp NIK',
    ),
    'relationshipWithBorrower' => 
    array (
      'required' => 'The relationship with borrower is required .',
    ),
    'ktpNik' => 
    array (
      'required' => 'The KTP NIK is required.',
      'min' => 'The KTP NIK number should be 16 number only.',
    ),
    'loanApplicantAddressLine1' => 
    array (
      'required' => 'The street line 1 is required.',
    ),
    'loanApplicantAddressLine2' => 
    array (
      'required' => 'The street line 2 is required.',
    ),
    'subDistrict' => 
    array (
      'required' => 'The sub district is required.',
    ),
    'district' => 
    array (
      'required' => 'The district is required',
    ),
    'loanApplicantPostalCode' => 
    array (
      'required' => 'The postal code is required.',
      'min' => 'The postal code length must be between 5 to 8 digits.',
      'max' => 'The postal code length must be between 5 to 8 digits.',
    ),
    'loanApplicantHasLivedHere' => 
    array (
      'required' => 'The has lived here status is required.',
    ),
    'loanApplicantDurationOfStay' => 
    array (
      'required' => 'The duration of stay is required.',
    ),
    'loanApplicanthomeOwnershipStatus' => 
    array (
      'required' => 'The home ownership status is required.',
    ),
    'cityId' => 
    array (
      'required' => 'The city is required.',
    ),
    'provincesId' => 
    array (
      'required' => 'The provinces is required.',
    ),
    'loanApplicantCountryId' => 
    array (
      'required' => 'The country is required.',
    ),
    'loanApplicantAddressName' => 
    array (
      'required' => 'The address name is required.',
    ),
    'loanApplicantBusinessType' => 
    array (
      'required' => 'The type is required.',
    ),
    'loanApplicantBusinessName' => 
    array (
      'required' => 'The business name is required.',
    ),
    'loanApplicantBusinessWebsite' => 
    array (
      'required' => 'The website is required.',
    ),
    'loanApplicantBusinessEmail' => 
    array (
      'required' => 'The business email is required.',
      'email' => 'The email should be \'xyz@gmail.com\' format.',
      'unique' => 'The email is already exist.',
    ),
    'loanApplicantBusinessPhone' => 
    array (
      'required' => 'The phone is required.',
      'min' => 'The phone number must be at least 9 characters.',
    ),
    'loanApplicantBusinessFirstName' => 
    array (
      'required' => 'The first name is required.',
    ),
    'loanApplicantBusinessLastName' => 
    array (
      'required' => 'The last name is required.',
    ),
    'loanApplicantBusinessAverageSales' => 
    array (
      'required' => 'The average sales is required.',
    ),
    'loanApplicantBusinessEstablish' => 
    array (
      'required' => 'The established date is required.',
      'date' => 'The establish in date is not a valid date.',
    ),
    'loanApplicantBusinessNoOfEmployee' => 
    array (
      'required' => 'The number of employee is required.',
    ),
    'loanApplicantSiupNumber' => 
    array (
      'required' => 'The Siup number is required.',
    ),
    'loanApplicantCategory' => 
    array (
      'required' => 'The category is required.',
    ),
    'loanApplicantOwnership' => 
    array (
      'required' => 'The ownership is required.',
      'gt' => 'The Ownership % must be gratar than 0 and less then 100.',
      'lte' => 'The Ownership % must be gratar than 0 and less then 100.',
      'numeric' => 'The ownership is should be numbers only.',
    ),
    'loanApplicantRelationshipWithBorrower' => 
    array (
      'required' => 'The relationship with borrower is required.',
    ),
    'loanApplicantBusinessNpwpImage' => 
    array (
      'required' => 'The NPWP image is required.',
    ),
    'loanApplicantBusinessLicenceImage' => 
    array (
      'required' => 'The license image is required.',
    ),
    'loanApplicantBankStatement' => 
    array (
      'required' => 'The bank statement file is required.',
    ),
    'loanApplicantBusinessDescription' => 
    array (
      'required' => 'The description is required.',
    ),
    'loanBusinessAddressLine1' => 
    array (
      'required' => 'The street line 1 is required.',
    ),
    'loanBusinessAddressLine2' => 
    array (
      'required' => 'The street line 2 is required.',
    ),
    'loanBusinessAddressSubDistrict' => 
    array (
      'required' => 'The sub district is required.',
    ),
    'loanBusinessAddressDistrict' => 
    array (
      'required' => 'The district is required.',
    ),
    'loanBusinessAddressProvinces' => 
    array (
      'required' => 'The provinces is required.',
    ),
    'loanBusinessAddressCity' => 
    array (
      'required' => 'The city is required.',
    ),
    'loanBusinessAddressCountry' => 
    array (
      'required' => 'The country is required.',
    ),
    'loanBusinessAddressPostalCode' => 
    array (
      'required' => 'The postal code is required.',
    ),
    'otherMemberEmail' => 
    array (
      'required' => 'The other member email is required',
      'email' => 'The email should be \'xyz@gmail.com\' format.',
    ),
    'city_business' => 
    array (
      'required_if' => 'The city is required.',
    ),
    'mobile' => 
    array (
      'min' => 'The mobile must be between 9 and 16 digits.',
      'max' => 'The mobile must be between 9 and 16 digits.',
      'required' => 'The mobile number is required.',
    ),
    'designation' => 
    array (
      'required' => 'The designation is required.',
    ),
    'department' => 
    array (
      'required' => 'The department is required.',
    ),
    'approverPasswordMatch' => 'Your Password is incorrect',
    'pkp_file' => 
    array (
      'required' => 'The PKP file is required.',
      'mimes' => 'The PKP file must be a file of type: jpeg,png,doc,docs,pdf.',
    ),
    'name' => 
    array (
      'required' => '',
      'regex' => '',
    ),
    'contactPersonName' => 
    array (
      'required' => 'The First Name is required.',
      'max' => 'First Name must not be greater than 255.',
    ),
    'contactPersonLastName' => 
    array (
      'required' => '',
      'max' => '',
    ),
    'nib' => 
    array (
      'required' => '',
      'max' => 'The NIB must not be greater than 13.',
    ),
    'npwp' => 
    array (
      'required' => '',
    ),
    'companyType' => 
    array (
      'required' => '',
    ),
    'profile_username' => 
    array (
      'required' => 'The Profile Username field is required.',
    ),
    'approval_comment' => 
    array (
      'required' => '',
    ),
    'contactPersonEmail' => 
    array (
      'required' => 'The Email field is required.',
      'unique' => 'The email is already exist.',
    ),
    'company_name' => 
    array (
      'required' => 'The Company Name field is required.',
    ),
    'alternate_email' => 
    array (
      'email' => 'The email should be \'xyz@gmail.com\' format.',
    ),
    'fax' => 
    array (
      'max' => 'The Fax Number must not be greater than 255.',
    ),
    'license' => 
    array (
      'max' => 'The Licence must not be greater than 255.',
    ),
    'facebook' => 
    array (
      'url' => 'The Facebook must be a valid URL.',
    ),
    'twitter' => 
    array (
      'url' => 'The Twitter must be a valid URL.',
    ),
    'linkedIn' => 
    array (
      'url' => 'The LinkedIn must be a valid URL.',
    ),
    'youtube' => 
    array (
      'url' => 'The YouTube must be a valid URL.',
    ),
    'instagram' => 
    array (
      'url' => 'The Instagram must be a valid URL.',
    ),
    'website' => 
    array (
      'url' => 'The Website must be a valid URL.',
    ),
    'contactPersonMobile' => 
    array (
      'max' => '',
      'required' => '',
    ),
    'portfolio_type' => 
    array (
      'required' => '',
    ),
    'portfolioImage' => 
    array (
      'required' => '',
    ),
    'category' => 
    array (
      'required' => '',
    ),
    'highlightImage' => 
    array (
      'required' => '',
    ),
    'firstname' => 
    array (
      'required' => '',
    ),
    'lastname' => 
    array (
      'required' => '',
    ),
    'description' => 
    array (
      'required' => '',
    ),
    'coreTeamImage' => 
    array (
      'required' => 'Core Team Image is required.',
    ),
  ),
  'user_added' => 'User Added Successfully',
  'user_invited' => 'User Invited Successfully',
  'user_deleted' => 'User Deleted Successfully',
  'email_already_exist' => 'Email id is already exist',
  'user_data_exist' => 'User Data Already Exist',
  'approval_members' => 'Do you want to add members for approval process ?',
  'attributes' => 
  array (
    'bankAccountHolderName' => 'bank account holder name',
    'bankAccountNumber' => 'bank account number',
    'bankName' => 'bank name',
  ),
  'values' => 
  array (
    'loanApplicantBusinessLicenceImage' => 
    array (
      '' => 'Business Licence Image required',
    ),
    'loanApplicantBusinessEmail' => 
    array (
      'harshil' => 
      array (
        '105@yopmail' => 
        array (
          'com' => '',
        ),
      ),
    ),
    'pkp_file' => 
    array (
      '' => '',
    ),
    'name' => 
    array (
      '' => '',
      'Chetak Pvt' => 
      array (
        ' Ltd' => 
        array (
          '' => '',
        ),
      ),
      'Shah & 9' => '',
    ),
    'contactPersonName' => 
    array (
      '' => '',
    ),
    'contactPersonLastName' => 
    array (
      '' => '',
    ),
    'nib' => 
    array (
      '' => '',
    ),
    'npwp' => 
    array (
      '' => '',
    ),
    'companyType' => 
    array (
      '' => '',
    ),
    'profile_username' => 
    array (
      '' => '',
    ),
    'approval_comment' => 
    array (
      '' => '',
    ),
    'approve_password' => 
    array (
      'Blitznet@12' => '',
    ),
    'contactPersonEmail' => 
    array (
      '' => '',
    ),
    'email' => 
    array (
      'harshil' => 
      array (
        420 => '',
      ),
      's' => '',
      '' => '',
    ),
    'website' => 
    array (
      'asd' => '',
      's' => '',
    ),
    'alternate_email' => 
    array (
      's' => '',
    ),
    'contactPersonMobile' => 
    array (
      '222222222222222222222222222' => '',
      '999999999999222222222222222222222222222' => '',
      '' => '',
      '222222222222222222222222222222' => '',
    ),
    'mobile' => 
    array (
      '' => '',
    ),
    'facebook' => 
    array (
      'a' => '',
    ),
    'twitter' => 
    array (
      'f' => '',
    ),
    'linkedIn' => 
    array (
      's' => '',
    ),
    'youtube' => 
    array (
      'g' => '',
    ),
    'instagram' => 
    array (
      'd' => '',
    ),
    'company_name' => 
    array (
      '' => '',
    ),
    'portfolio_type' => 
    array (
      '' => '',
    ),
    'portfolioImage' => 
    array (
      '' => '',
    ),
    'category' => 
    array (
      '' => '',
    ),
    'highlightImage' => 
    array (
      '' => '',
    ),
    'firstname' => 
    array (
      '' => '',
    ),
    'lastname' => 
    array (
      '' => '',
    ),
    'designation' => 
    array (
      '' => '',
    ),
    'description' => 
    array (
      '' => '',
    ),
    'coreTeamImage' => 
    array (
      '' => '',
    ),
    'net_income' => 
    array (
      'asdasd' => '',
    ),
    'department' => 
    array (
      '' => '',
    ),
    'role' => 
    array (
      '' => '',
    ),
  ),
  'user_updated' => 'User updated successfully',
  'ktpImage' => 'Ktp image required',
  'ktpSelfiImage' => 'KtpSelfi  image required',
  'otherKtpImage' => 'Other KTP image Required.',
  'familyCardImage' => 'Family card image Required.',
  'loanApplicantBusinessNpwpImage' => 'Business Npwp image required',
  'loanApplicantBusinessLicenceImage' => 'Business Licence Image required',
  'loanApplicantBankStatement' => 'Bank statement required',
  'loan_amount_need_minimum' => 'Loan amount should be less than limit.',
  'loan_application_not_found' => 'Loan Application Not Found.',
  'loanApplicantOwnership' => 
  array (
    'required' => 'The ownership is required.',
    'gt' => 'The Ownership % must be gratar than 0 and less then 100.',
    'lt' => 'The Ownership % must be gratar than 0 and less then 100.',
  ),
  'city' => 
  array (
    'required' => 'Other city is required.',
  ),
  'state' => 
  array (
    'required' => 'Other state is required.',
  ),
  'city_business' => 
  array (
    'required' => 'Other city is required.',
  ),
  'state_business' => 
  array (
    'required' => 'Other state is required.',
  ),
  'loanApplicantDurationOfStay' => 
  array (
    'gt' => 'The loan applicant duration of stay must be greater than 0.',
  ),
  'mobile_varify' => 'Mobile Verification',
  'verifyotp' => 'Verify OTP',
  'max_attempt' => 'OTP failed because of too many requests',
  'invalidotp' => 'Please enter valid OTP.',
  'otpexpired' => 'Your OTP is Expired.',
  'mobvalida' => 'Please enter valid mobile number.',
  'preferctry' => 'We are only accepting Indonesian and Indian phone numbers.',
  'emailvalid' => 'Please enter valid Email-id.',
  'mobileexits' => 'Mobile number already exits',
);

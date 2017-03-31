<?php
use Respect\Validation\Validator as DataValidator;

class BanUserController extends Controller {
    const PATH = '/ban';
    const METHOD = 'POST';

    public function validations() {
        return [
            'permission' => 'staff_1',
            'requestData' => [
                'email' => [
                    'validation' => DataValidator::email(),
                    'error' => ERRORS::INVALID_EMAIL
                ]
            ]
        ];
    }

    public function handler() {
        $email = Controller::request('email');

        $banRow = Ban::getDataStore($email,'email');
        if($banRow->isNull()) {
            $ban = new Ban();

            $ban->setProperties(array(
                'email' => $email
            ));

            $ban->store();

            Log::createLog('BAN_USER', $email);

            Response::respondSuccess();
        } else {
            Response::respondError(ERRORS::ALREADY_BANNED);
        }
    }
}
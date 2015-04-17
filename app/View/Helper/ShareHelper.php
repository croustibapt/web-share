<?php

App::uses('AppHelper', 'View/Helper');

class ShareHelper extends AppHelper {
    public function getShareDetailsRequestStatusLabel($status = NULL) {
        $label = 'Unknown';

        if ($status == SHARE_REQUEST_STATUS_PENDING) {
            $label = 'En attente <i class="fa fa-question-circle"></i>';
        } else if ($status == SHARE_REQUEST_STATUS_ACCEPTED) {
            $label = 'Acceptée <i class="fa fa-check-circle"></i>';
        } else if ($status == SHARE_REQUEST_STATUS_DECLINED) {
            $label = 'Refusée <i class="fa fa-times-circle"></i>';
        } else if ($status == SHARE_REQUEST_STATUS_CANCELLED) {
            $label = 'Annulée <i class="fa fa-exclamation-circle"></i>';
        }

        return $label;
    }

    public function getShareDetailsRequestStatusClass($status = NULL) {
        $class = 'btn-default';

        if ($status == SHARE_REQUEST_STATUS_PENDING) {
            $class = 'btn-warning';
        } else if ($status == SHARE_REQUEST_STATUS_ACCEPTED) {
            $class = 'btn-success';
        } else if ($status == SHARE_REQUEST_STATUS_DECLINED) {
            $class = 'btn-danger';
        } else if ($status == SHARE_REQUEST_STATUS_CANCELLED) {
            $class = 'btn-default';
        }

        return $class;
    }
}
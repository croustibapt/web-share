<?php

App::uses('AppHelper', 'View/Helper');

class ShareHelper extends AppHelper {
    public function getShareDetailsRequestStatusLabel($status = NULL) {
        $label = 'Unknown';

        if ($status == SHARE_REQUEST_STATUS_PENDING) {
            $label = 'Demande en attente <i class="fa fa-question-circle"></i>';
        } else if ($status == SHARE_REQUEST_STATUS_ACCEPTED) {
            $label = 'Demande acceptée <i class="fa fa-check-circle"></i>';
        } else if ($status == SHARE_REQUEST_STATUS_DECLINED) {
            $label = 'Demande refusée <i class="fa fa-times-circle"></i>';
        } else if ($status == SHARE_REQUEST_STATUS_CANCELLED) {
            $label = 'Demande annulée <i class="fa fa-exclamation-circle"></i>';
        }

        return $label;
    }

    public function getShareDetailsRequestStatusClass($status = NULL) {
        $class = 'default';

        if ($status == SHARE_REQUEST_STATUS_PENDING) {
            $class = 'warning';
        } else if ($status == SHARE_REQUEST_STATUS_ACCEPTED) {
            $class = 'success';
        } else if ($status == SHARE_REQUEST_STATUS_DECLINED) {
            $class = 'danger';
        } else if ($status == SHARE_REQUEST_STATUS_CANCELLED) {
            $class = 'default';
        }

        return $class;
    }

    public function getRequestStatusIcon($status = NULL) {
        $icon = '';

        if ($status == SHARE_REQUEST_STATUS_PENDING) {
            $icon = '<i class="fa fa-question-circle"></i>';
        } else if ($status == SHARE_REQUEST_STATUS_ACCEPTED) {
            $icon = '<i class="fa fa-check-circle"></i>';
        } else if ($status == SHARE_REQUEST_STATUS_DECLINED) {
            $icon = '<i class="fa fa-times-circle"></i>';
        } else if ($status == SHARE_REQUEST_STATUS_CANCELLED) {
            $icon = '<i class="fa fa-exclamation-circle"></i>';
        }

        return $icon;
    }
}
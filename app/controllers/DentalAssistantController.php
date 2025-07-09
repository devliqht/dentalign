<?php

class DentalAssistantController extends Controller
{
    public function dashboard()
    {
        $this->requireAuth();
        $this->requireRole("ClinicStaff");
        $this->requireStaffType("DentalAssistant");

        $data = [
            "user" => $this->getAuthuser(),
        ];
        
        $layoutConfig = [
            "title" => "Dental Assistant Dashboard",
            "hideHeader" => false,
            "hideFooter" => false,
        ];

        $this->view("pages/staff/dentalassistant/Dashboard", $data, $layoutConfig);
    }
}
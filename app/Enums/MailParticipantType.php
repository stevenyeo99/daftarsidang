<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class MailParticipantType extends Enum
{
    const MAHASISWA = 0;
    const BAAK = 1;
    const ADMIN_PRODI = 2;
    const DOSEN_PEMBIMBING = 3;
    const DOSEN_PENGUJI = 4;
    const DOSEN_PEMBIMBING_BACKUP = 5;
    const FINANCE = 6;
}
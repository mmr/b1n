/* Copyright (c) 2007, B1N.ORG
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the B1N.ORG organization nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL B1N.ORG OR ITS CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
package b1n.framework.persistence.bo;

import java.util.HashSet;
import java.util.Set;

import javax.persistence.Entity;
import javax.persistence.ManyToMany;
import javax.persistence.ManyToOne;

/**
 * Doctor Business Object, to test inheritance with PersonBo, <code>@ManyToOne</code> with HospitalBo and <code>@ManyToMany</code> with HealthyInsuranceBo
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 */
@Entity
public class DoctorBo extends PersonBo {
    @ManyToOne
    private HospitalBo hospital;

    @ManyToMany
    private Set<HealthInsuranceBo> healthInsurances = new HashSet<HealthInsuranceBo>();

    public HospitalBo getHospital() {
        return hospital;
    }

    public void setHospital(HospitalBo hospital) {
        this.hospital = hospital;
    }

    public Set<HealthInsuranceBo> getHealthInsurances() {
        return healthInsurances;
    }

    protected void setHealthInsurances(Set<HealthInsuranceBo> healthInsurances) {
        this.healthInsurances = healthInsurances;
    }

    public void addHealthInsurance(HealthInsuranceBo healthInsurance) {
        this.healthInsurances.add(healthInsurance);
    }

    public void removeHealthInsurance(HealthInsuranceBo healthInsurance) {
        this.healthInsurances.remove(healthInsurance);
    }
}
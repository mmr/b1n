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
package org.b1n.framework.persistence.entity;

import java.util.HashSet;
import java.util.Set;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.ManyToMany;

import org.b1n.framework.persistence.SimpleEntity;

/**
 * Health Insurance Business Object, to test <code>@ManyToMany</code> with DoctorBo.
 * @author Marcio Ribeiro (mmr)
 * @created Mar 27, 2007
 */
@Entity
public class HealthInsurance extends SimpleEntity {
    private String name;

    @ManyToMany
    private Set<Doctor> doctors = new HashSet<Doctor>();

    @Column(nullable = false)
    private InsuranceRating rating;

    /**
     * @return doctors.
     */
    public Set<Doctor> getDoctors() {
        return doctors;
    }

    /**
     * @param doctors doctors.
     */
    protected void setDoctors(final Set<Doctor> doctors) {
        this.doctors = doctors;
    }

    /**
     * Add doctor.
     * @param doctor doctor.
     */
    public void addDoctor(final Doctor doctor) {
        this.doctors.add(doctor);
    }

    /**
     * Remove doctor.
     * @param doctor doctor.
     */
    public void removeDoctor(final Doctor doctor) {
        this.doctors.remove(doctor);
    }

    /**
     * @return name.
     */
    public String getName() {
        return name;
    }

    /**
     * @param name name.
     */
    public void setName(final String name) {
        this.name = name;
    }

    /**
     * @return the rating.
     */
    public InsuranceRating getRating() {
        return rating;
    }

    /**
     * @param rating the rating to set.
     */
    public void setRating(final InsuranceRating rating) {
        this.rating = rating;
    }
}
